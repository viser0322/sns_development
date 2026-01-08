
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

  // さらに返信を表示
  $(document).on('click', '.js--reply--display', function(e) {
    const target_id = e.target.id;
    var id = target_id.replace('js--reply--display--','');

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    });
    $.ajax({
      type: "POST",
      url: "/post/get/"+id,
      data: {},
    })
    .done((data) => {
      let parent = e.target.parentElement;

      if (data.length != 0) {
        data.forEach((key, index) => {
          if(typeof key.id != 'undefined'){
            let str = '<div  style="padding:2rem; border-top: solid 1px #E6ECF0; border-bottom: solid 1px #E6ECF0;">'+
            '<div>>> <a href="/post/'+key.to_posts+'">'+key.to_posts+'</a></div>'+
            '<div>';
              if (key.content != null) {
               str = str + nl2br(addLink(key.content));
              }
            '</div>';

            if (key.file_name != null) {
              str = str + '<div><a href="/storage/content/'+key.file_name+'" data-lightbox="group_'+key.id+'"><img src="/storage/content/'+key.file_name+'" alt="" style="width: 80%"></a></div>';
            }
           
            let date = new Date(key.created_at).toLocaleString("ja", { year: 'numeric', month: '2-digit', day: '2-digit', hour12:false, hour:'2-digit', minute:'2-digit' ,second: '2-digit'});
            display_date = date.toLocaleString("ja").replace(/[/]/g,'-');

            str = str + '<div>by <a href="/profile/'+key.user_id+'">'+key.user_name+'</a></div>'+
            '<div>'+
              '<a href="/post/'+key.id+'">'+display_date+'</a>'+
            '</div>'+
            '<div>'+
              '<button type="button" class="btn btn-danger reply-btn js--reply" id="js--reply--'+key.id+'">返信</button>'+
              '<button type="button" class="btn btn-danger link-btn js--link" id="js--link--'+key.id+'">リンクを生成</button>';

            if (key.user_id != user_id) {
              str = str + '<button type="button" class="btn btn-danger reaction-btn js--reaction" id="js--reaction--'+key.id+'">リアクション</button>';
            }
            else {
              str = str + '<button type="button" class="btn btn-danger" onclick="deleteSubmit('+key.id+')">削除</button>';
            }
            str = str + '</div>'+
            '<div id="js--reaction--list--'+key.id+'" class="js--reaction--list">'+
            '</div>'+
            '<button type="button" class="btn btn-danger reply-btn js--reply--display" id="js--reply--display--'+key.id+'">・・・さらに返信を表示・・・</button>'+
            '<div id="js--toposts--list--'+key.id+'" class="">'+
            '</div>';

            $('#js--toposts--list--'+key.to_posts).html(str);
          }
        });
      } else {
        $('#js--toposts--list--'+id).html('<div>投稿がありません。</div>');
      }
    });
  });

  // URLをaタグにして返却
  function addLink(str) {
    //正規表現で検索するurlの形を設定
    var url = /((h?)(ttps?:\/\/[a-zA-Z0-9.\-_@:/~?%&;=+#',()*!]+))/g; // ']))/;

    //URLにaタグをつけて返す関数　
    var generate_link = function (all, url, h, href) {
        return '<a href="h' + href + '" target="__blank">' + url + '</a>';
    }

    //引数strの中にあるUrlをリンク付きに置換
    return str.replace(url, generate_link);
  }

  // nl2br関数の定義
  function nl2br(str) {
    // 改行コード（\n）を、<br>タグに置き換える
    return str.replace(/\n/g, '<br>');
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
    var data = str.split('--');

    post_id = data[0];
    emoji_id = data[1];
    emoji_skin = data[2];

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
    const reaction = [];
    data.forEach((key, index) => {
      if(typeof key.post_id != 'undefined'){
        let emoji_skin = 1;
        if(key.emoji_skin != null){
          emoji_skin = key.emoji_skin;
        }
        $('#js--reaction--list--'+key.post_id).append('<button type="button" onclick="reaction_emoji(this)" class="js--reaction--emoji" id="reaction--'+key.post_id+'--'+key.emoji_id+'--'+emoji_skin+'"><em-emoji id="'+key.emoji_id+'" skin="'+emoji_skin+'" size="1em"></em-emoji><div>'+key.reaction_count+'</div></button>');
      }
    });
  });
</script>
@endsection

@section('content')
<div style="height:100%; background-color: #E6ECF0;">
<span class="text-danger">{{ session('error_msg') }}</span>
  <div class="wrapper" style="margin: 0 auto; width: 50%; height: 100%; background-color: white;">

  @if(isset($post))
  <div id="post_{{ $post->id }}" style="padding:2rem; border-top: solid 1px #E6ECF0; border-bottom: solid 1px #E6ECF0;">
    @if(isset($post->type))
    @if($post->type == 1)
    <div>>> <a href="/post/{{ $post->to_posts }}">{{ $post->to_posts }}</a></div>
    @endif
    @endif
    <div>
      @if(isset($post_content[$post->id])){!! nl2br($post_content[$post->id]) !!}
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
  @else
  <div style="padding:2rem; border-top: solid 1px #E6ECF0; border-bottom: solid 1px #E6ECF0;">
  投稿がありません。
  </div>
  @endif

  <div>
  @if(isset($to_posts))
    @foreach($to_posts as $to_post)
      <div  style="padding:2rem; border-top: solid 1px #E6ECF0; border-bottom: solid 1px #E6ECF0;">
        @if(isset($to_post->type))
        @if($to_post->type == 1)
        <div>>> <a href="/post/{{ $to_post->to_posts }}">{{ $to_post->to_posts }}</a></div>
        @endif
        @endif
        <div>
          @if(isset($to_post_contents[$to_post->id])){!! nl2br($to_post_contents[$to_post->id]) !!}
          @else{!! nl2br($to_post->content) !!}
          @endif
        </div>
        @if(isset($to_post->file_name))
        <div><a href="{{ asset('storage/content/'.$to_post->file_name) }}" data-lightbox="group_{{$to_post->id}}"><img src="{{ asset('storage/content/'.$to_post->file_name) }}" alt="" style="width: 80%"></a></div>
        @endif
        <div>by <a href="/profile/{{ $to_post->user_id }}">{{ $to_post->user_name }}</a></div>
        <div>
          <a href="/post/{{ $to_post->id }}">{{ $to_post->created_at }}</a>
        </div>
        <div>
          <button type="button" class="btn btn-danger reply-btn js--reply" id="js--reply--{{ $to_post->id }}">返信</button>
          <button type="button" class="btn btn-danger link-btn js--link" id="js--link--{{ $to_post->id }}">リンクを生成</button>
        @if($to_post->user_id != $user->id)
          <button type="button" class="btn btn-danger reaction-btn js--reaction" id="js--reaction--{{ $to_post->id }}">リアクション</button>
        @endif
        @if($to_post->user_id == $user->id)
          <button type="button" class="btn btn-danger" onclick="deleteSubmit({{ $to_post->id }})">削除</button>
        @endif
        </div>
        <div id="js--reaction--list--{{ $to_post->id }}" class="js--reaction--list">
        </div>
        <button type="button" class="btn btn-danger reply-btn js--reply--display" id="js--reply--display--{{ $to_post->id }}">・・・さらに返信を表示・・・</button>
        <div id="js--toposts--list--{{ $to_post->id }}" class="">
        </div>
      </div>
    @endforeach
  @endif
  </div>

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
              user_id: user_id,
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

  </div>
</div>
@endsection
