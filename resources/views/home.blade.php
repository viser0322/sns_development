@extends('layout')

@section('title')
{{ $title }}
@endsection

@section('page_css')
<style>
em-emoji-picker {
  display: none;
}
</style>
@endsection

@section('page_js')
<!-- <script src="{{ asset('js/reaction.js').'?'.time() }}"></script> -->
<script>
  // 削除
  function deleteSubmit(id) {
    var delete_confirm = confirm('削除してよろしいでしょうか？');

    if(delete_confirm == true) {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      });
      $.ajax({
        type: "DELETE",
        url: "/post/"+id+"/delete",
        data: {},
      })
      .done((result) => {
        $('#post_'+id).remove();
      });
    }
  }

  // 共有：リンク生成
  $(document).on('click', '.js--link', function(e) {
    const target_id = e.target.id;
    var id = target_id.replace('js--link--','');

    // 追記するデータ
    var add_text = "<?echo config('app.url');?>"+'/post/'+id+"\n";
    // テキストボックスのデータを取得
    var get_data = String($("#post_input").val());
    // 取得データと追記文言をくっつけて出力
    $("#post_input").val(get_data + add_text);
  });

  // 返信
  $(document).on('click', '.js--reply', function(e) {
    const target_id = e.target.id;
    var id = target_id.replace('js--reply--','');
    $("#post_type").val('1');
    $("#to_posts").val(id);

    let element = document.getElementById('display_to_posts_div');
    var createElement = '<p id="to_posts_id">>> '+id+'\n</p>'+'<button type="button" onclick="cancel_to_posts()">×</button>';

    element.innerHTML = createElement;
  });

  // 返信キャンセル
  function cancel_to_posts () {
    let target = document.getElementById('display_to_posts_div');

    while (target.firstChild){
      target.removeChild(target.firstChild);
    }
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/emoji-mart@latest/dist/browser.js"></script>
<script>
  let user_id = <?=Auth::guard('user')->user()->id?>;
  let post_id = null;
  let emoji_id = null;
  let emoji_skin = null;

  // リアクション
  $(document).on('click', '.js--reaction', function(e) {
    const target_id = e.target.id;
    var id = target_id.replace('js--reaction--','');
    post_id = id;

    const display_target = document.getElementsByTagName("em-emoji-picker");
    display_target[0].style.display = "flex";
  });

  function reaction_emoji (e) {
    const target_id = e.id;
    var str = target_id.replace('reaction--','');
    var result = str.split('--');

    post_id = result[0];
    emoji_id = result[1];
    emoji_skin = result[2];

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    });
    $.ajax({
      type: "POST",
      //ここでデータの送信先URLを指定します。
      url: "/reaction/regist",
      data: {
        emoji_id: emoji_id,
        emoji_skin: emoji_skin,
        post_id: post_id,
        user_id: user_id,
      },
    })
    .done((result) => {
      let reaction_count = Number(e.children[1].innerText);

      if (result['type'] == "regist" || result['type'] == "restore") {
        e.children[1].innerText = reaction_count + 1;
      } else if (result['type'] == "delete") {
        if (reaction_count == 1) {
          e.remove();
        } else {
          e.children[1].innerText = reaction_count - 1;
        }
      }
    });
  }
</script>
<script async>
  // リアクション情報取得
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
  });
  $.ajax({
    type: "POST",
    //ここでデータの送信先URLを指定します。
    url: "/reaction/count",
    data: {},
  })
  //通信が成功したとき
  .done((data) => {
    data.forEach((key, index) => {
      if(typeof key.post_id != 'undefined'){
        let disable = "";
        let emoji_skin = 1;
        if(key.emoji_skin != null){
          emoji_skin = key.emoji_skin;
        }
        if(key.user_id == user_id){
          disable = "disabled";
        }
        $('#js--reaction--list--'+key.post_id).append('<button type="button" onclick="reaction_emoji(this)" class="js--reaction--emoji" id="reaction--'+key.post_id+'--'+key.emoji_id+'--'+emoji_skin+'" '+disable+'><em-emoji id="'+key.emoji_id+'" skin="'+emoji_skin+'" size="1em"></em-emoji><div>'+key.reaction_count+'</div></button>');
      }
    });
  });
</script>

@endsection

@section('content')
<div style="height:100%; background-color: #E6ECF0;">
<span class="text-danger">{{ session('error_msg') }}</span>
  <div class="wrapper" style="margin: 0 auto; width: 50%; height: 100%; background-color: white;">
    <form action="/home" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
      <div style="background-color: #E8F4FA; text-align: center;">
      <div id="display_to_posts_div"></div>
        <input type="hidden" name="type" id="post_type" value="0">
        <input type="hidden" name="to_posts" id="to_posts" value="">
        <textarea name="post" id="post_input" style="margin: 1rem; padding: 0 1rem; width: 70%; border-radius: 6px; border: 1px solid #ccc; height: 2.3rem;" placeholder="今どうしてる？"></textarea>
        <input type="file" id="file" accept=".jpg,.jpeg,.png,.gif" name="file">
        <button type="submit" style="background-color: #2695E0; color: white; border-radius: 10px; padding: 0.5rem;">投稿</button>
      </div>
      @if($errors->first('post')) <!-- 追加 -->
        <p style="font-size: 0.7rem; color: red; padding: 0 2rem;">※{{$errors->first('post')}}</p>
      @endif
    </form>
    <div class="post-wrapper"> <!-- この辺追加 -->
      @if(isset($posts))
        @foreach($posts as $post)
        <div id="post_{{ $post->id }}" style="padding:2rem; border-top: solid 1px #E6ECF0; border-bottom: solid 1px #E6ECF0;">
          @if(isset($post->type))
          @if($post->type == 1)
          <div>>> <a href="/post/{{ $post->to_posts }}">{{ $post->to_posts }}</a></div>
          @endif
          @endif
          <div>
            @if(isset($post_contents[$post->id])){!! nl2br($post_contents[$post->id]) !!}
            @else{!! nl2br($post->content) !!}
            @endif
          </div>
          @if(isset($post->file_name))
          <div><a href="{{ asset('storage/content/'.$post->file_name) }}" data-lightbox="group_{{$post->id}}"><img src="{{ asset('storage/content/'.$post->file_name) }}" alt="" style="width: 80%"></a></div>
          @endif
          <div>by <a href="/profile/{{ $post->user_id }}">{{ $post->user_name }}</a></div>
          <div>
            <a href="/post/{{ $post->id }}">{{ $post->created_at }}</a>
          </div>
          <div>
            <button type="button" class="btn btn-danger reply-btn js--reply" id="js--reply--{{ $post->id }}">返信</button>
            <button type="button" class="btn btn-danger link-btn js--link" id="js--link--{{ $post->id }}">リンクを生成</button>
          @if($post->user_id != $user->id)
            <button type="button" class="btn btn-danger reaction-btn js--reaction" id="js--reaction--{{ $post->id }}">リアクション</button>
          @endif
          @if($post->user_id == $user->id)
            <button type="button" class="btn btn-danger" onclick="deleteSubmit({{ $post->id }})">削除</button>
          @endif
          </div>
          <div id="js--reaction--list--{{ $post->id }}" class="js--reaction--list">
          </div>
        </div>
        @endforeach
      @endif
      <div id="picker" style="padding:2rem; border-top: solid 1px #E6ECF0;">
      <script src="https://cdn.jsdelivr.net/npm/emoji-mart@latest/dist/browser.js"></script>
      <script>
        var target = document.getElementById('picker');
        var pickerOptions = { 
          locale:"ja",
          onClickOutside: function(e){
            if (!e.target.className.match("js--reaction")) {
              const display_target = document.getElementsByTagName("em-emoji-picker");
              display_target[0].style.display = "none";
            }
          },
          onEmojiSelect: function(e){
            if(typeof e.skin != 'undefined') {
              emoji_skin = e.skin;
            } else {
              emoji_skin = 1;
            }
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              }
            });
            $.ajax({
              type: "POST",
              //ここでデータの送信先URLを指定します。
              url: "/reaction/regist",
              data: {
                emoji_id: e.id,
                emoji_skin: emoji_skin,
                post_id: post_id,
              },
            })
            .done((result) => {
              let target = document.getElementById('reaction--'+post_id+'--'+e.id+'--'+emoji_skin);
              let reaction_count = 1;

              if (result['type'] == "regist") {
                if (target != null) {
                // すでにリアクションが存在する場合
                // カウントを足す
                  reaction_count = Number(target.children[1].innerText);
                  target.children[1].innerText = reaction_count + 1;
                } else {
                // リアクションが存在しない場合
                // ボタンを追加
                  $('#js--reaction--list--'+post_id).append('<button type="button" onclick="reaction_emoji(this)" class="js--reaction--emoji" id="reaction--'+post_id+'--'+e.id+'--'+emoji_skin+'"><em-emoji id="'+e.id+'" skin="'+emoji_skin+'" size="1em"></em-emoji><div>1</div></button>');
                }
              } else if (result['type'] == "restore") {
                if (target != null) {
                // すでにリアクションが存在する場合
                // 一人1リアクションなので追加しない
                } else {
                // リアクションが存在しない場合
                // ボタンを追加
                  $('#js--reaction--list--'+post_id).append('<button type="button" onclick="reaction_emoji(this)" class="js--reaction--emoji" id="reaction--'+post_id+'--'+e.id+'--'+emoji_skin+'"><em-emoji id="'+e.id+'" skin="'+emoji_skin+'" size="1em"></em-emoji><div>1</div></button>');
                }
              } else if (result['type'] == "delete") {
                if (target != null) {
                  reaction_count = Number(target.children[1].innerText);
                  if (reaction_count == 1) {
                    target.remove();
                  } else {
                    target.children[1].innerText = reaction_count - 1;
                  }
                } else {
                  // target.children[1].innerText = reaction_count - 1;
                }
              }
            });
          },
        }
        var picker = new EmojiMart.Picker(pickerOptions);
        target.appendChild(picker);
      </script>
    </div>
  </div>
</div>
@endsection
