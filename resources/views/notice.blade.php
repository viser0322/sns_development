
@extends('layout')

@section('title')
{{ $title }}
@endsection

@section('page_css')
<style>
#copyTarget {
  border: none !important;
  background-color: inherit !important;
  box-shadow: none !important;
  color: rgba(0, 0, 0, 0) !important;
}
em-emoji-picker {
  display: none;
}
</style>
@endsection

@section('page_js')
<script src="https://cdn.jsdelivr.net/npm/emoji-mart@latest/dist/browser.js"></script>
<script>
  let user_id = <?=Auth::guard('user')->user()->id?>;
  let post_id = null;
  let emoji_id = null;
  let emoji_skin = null;

  // リアクション
  $('.js--reaction').on('click', (e) => {
    const target_id = e.target.id;
    var id = target_id.replace('js--reaction--','');
    post_id = id;

    const display_target = document.getElementsByTagName("em-emoji-picker");
    display_target[0].style.display = "flex";
  });

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
<!-- <script src="{{ asset('js/reaction.js').'?'.time() }}"></script> -->
<script>

</script>
@endsection

@section('content')
<div style="height:100%; background-color: #E6ECF0;">
<span class="text-danger">{{ session('error_msg') }}</span>
  <div class="wrapper" style="margin: 0 auto; width: 50%; height: 100%; background-color: white;">
    <div class="post-wrapper">
      @if(isset($notices))
        @foreach($notices as $notice)

        @if($notice->type == 1)
        <div style="padding:2rem; border-top: solid 1px #E6ECF0; border-bottom: solid 1px #E6ECF0;">
          <div>{{ $notice->user_name }} さんより</div>
          <div>>> <a href="/post/{{ $notice->to_id }}">{{ $notice->to_id }}</a></div>
          <div>{{ $notice->content }}</div>
          @if(isset($notice->file_name))
          <div><img src="{{ asset('storage/content/'.$notice->file_name) }}" alt="" style="width: 80%"></div>
          @endif
          <div>
            <?
            $date = new DateTime($notice->created_at);
            $date->setTimeZone(new DateTimeZone('Asia/Tokyo'));
            ?>
            <a href="/post/{{ $notice->id }}">{{ $date->format('Y-m-d H:i:s') }}</a>
          </div>
          <div>
            <!-- <button type="button" class="btn btn-danger reply-btn js--reply" id="js--reply--{{ $notice->id }}">返信</button>
            <button type="button" class="btn btn-danger link-btn js--link" id="js--link--{{ $notice->id }}">リンクを生成</button> -->
          @if($notice->from_user_id != $user->id)
            <button type="button" class="btn btn-danger reaction-btn js--reaction" id="js--reaction--{{ $notice->id }}">リアクション</button>
          @endif
          @if($notice->from_user_id == $user->id)
            <button type="button" class="btn btn-danger" onclick="deleteSubmit({{ $notice->id }})">削除</button>
          @endif
          </div>
          <div id="js--reaction--list--{{ $notice->id }}" class="js--reaction--list">
          </div>
        </div>
        @endif

        @if($notice->type == 2)
        <div style="padding:2rem; border-top: solid 1px #E6ECF0; border-bottom: solid 1px #E6ECF0;">
          <div>{{ $notice->user_name }} さんより</div>
          <div>>> <a href="/post/{{ $notice->to_id }}">{{ $notice->to_id }}</a></div>
          <div>
            <em-emoji id="{{ $notice->emoji_id }}" skin="{{ $notice->emoji_skin }}" size="1em"></em-emoji>
          </div>
          <div>
            <?
            $date = new DateTime($notice->created_at);
            $date->setTimeZone(new DateTimeZone('Asia/Tokyo'));
            ?>
            <a href="/post/{{ $notice->id }}">{{ $date->format('Y-m-d H:i:s') }}</a>
          </div>
          <!-- <div> -->
            <!-- <button type="button" class="btn btn-danger reply-btn js--reply" id="js--reply--{{ $notice->id }}">返信</button>
            <button type="button" class="btn btn-danger link-btn js--link" id="js--link--{{ $notice->id }}">リンクを生成</button> -->
          <!-- @if($notice->from_user_id != $user->id)
            <button type="button" class="btn btn-danger reaction-btn js--reaction" id="js--reaction--{{ $notice->id }}">リアクション</button>
          @endif
          @if($notice->from_user_id == $user->id)
            <button type="button" class="btn btn-danger" onclick="deleteSubmit({{ $notice->id }})">削除</button>
          @endif -->
          <!-- </div> -->
          <!-- <div id="js--reaction--list--{{ $notice->id }}" class="js--reaction--list"> -->
          <!-- </div> -->
        </div>
        @endif

        @endforeach
      @endif
    </div>
  </div>


</div>

<div id="picker" style="padding:2rem; border-top: solid 1px #E6ECF0;"></div>

<form name="delete_post" action="" method="post" onsubmit="return confirm('削除しますか？');"">
  @csrf
  @method('delete')
</form>
@endsection
