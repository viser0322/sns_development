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
  $('.js--link').on('click', (e) => {
    const target_id = e.target.id;
    var id = target_id.replace('js--link--','');

    // 追記するデータ
    var add_text = url+'/post/'+id;
    // テキストボックスのデータを取得
    var get_data = String($("#post_input").val());
    // 取得データと追記文言をくっつけて出力
    $("#post_input").val(get_data + add_text);
  });

  // 返信
  $('.js--reply').on('click', (e) => {
    const target_id = e.target.id;
    var id = target_id.replace('js--reply--','');
    $("#post_type").val('1');
    $("#to_posts").val(id);

    let element = document.getElementById('display_to_posts_div');
    var createElement = '<p id="to_posts_id">>> '+id+'\n</p>'+'<button type="button" onclick="cancel_to_posts()">×</button>';

    element.insertAdjacentHTML('afterbegin', createElement);
  });

  // 返信キャンセル
  function cancel_to_posts () {
    let target = document.getElementById('display_to_posts_div');

    while (target.firstChild){
      target.removeChild(target.firstChild);
    }
  }

  // リアクション
  $('.js--reaction').on('click', (e) => {
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
    