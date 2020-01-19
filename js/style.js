    $(function() {
        //フッターを最下部に固定
        var $ftr = $('#footer');
        if (window.innerHeight > $ftr.offset().top + $ftr.outerHeight()) {
            $ftr.attr({
                'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) + 'px;'
            });
        }
      //お気に入り登録・削除
      var $favorite, favotripId;
      $favorite = $('.js-click-fov') || null;
      favotripId = $favorite.data('tripid') || null;
      if (favotripId !== undefined && favotripId !== null) {
          $favorite.on('click', function() {
              console.log('OK');
              var $this = $(this);
              $.ajax({
                  type: "POST",
                  url: "ajaxLike.php",
                  data: {
                      tripid: favotripId
                  }
              }).done(function(data) {
                  console.log('Ajax Success');
                  $this.text('.count').html(data);//追加
                  $this.toggleClass('active');
                  $('.count').css('display','none');
              }).fail(function(msg) {
                  console.log('Ajax Error');
              });
          });
      }

    // テキストエリアカウント
    var $countUp = $('#js-count'),
        $countView = $('#js-count-view');
    $countUp.on('keyup', function(e){
      $countView.html($(this).val().length);
    });
    //読み込み時
    $countUp.ready('', function(e){
      $countView.html($(this).val().length);
      console.log($countView);
    });

      //都道府県表示の切り替え
      $('.prefnum').click(function(){
        //Value属性の値を取得
        var target = $(this).attr("value");
        var target_pref = $(this).prev('input[name="pref-chk"]').val(target);
        
        if(target_pref.prop("checked") == true){
            $(".js-target").each(function(){
              $(this).animate({"opacity":0}, 300, function(){
                $(this).hide();

                  if($(this).hasClass(target)){
                    //条件を満たしているものを表示
                    $(this).show();
                    $(this).animate({"opacity" : 1}, 300);
                  }
              });
            });
        }else{
            //何もしない
        }
      });

      //再表示
      $('.prefnum').click(function(){
        var target = $(this).attr("value");
          $(".js-target").each(function(){
              if($(this).hasClass(target) || target == "prefAll"){
                $(this).show();
                $(this).animate({"opacity" : 1}, 300);
              }
        });
      });
    //削除の確認アラート
    $('#js-delete').on('click',function(){
        var result = window.confirm('現在表示しているたびの内容を削除します。よろしいですか?');

        if( result ) {
            console.log('OKがクリックされました');
             var confirm = $('input[name ="deletebutton"]').attr('name','deleteon');
            console.log(confirm);
        }
        else {
        console.log('キャンセルがクリックされました');
            return false;
        }
    });
      
    // メッセージ表示
    var $jsShowMsg = $('#js-show-msg');
    var msg = $jsShowMsg.text();
    if(msg.replace(/^[\s　]+|[\s　]+$/g, "").length){
      $jsShowMsg.slideToggle('slow');
      setTimeout(function(){ $jsShowMsg.slideToggle('slow'); }, 5000);
    }

    //投稿確認のアラート
    $('#js-posts').on('click',function(){
        var result = window.confirm('内容を投稿します。よろしいですか?');
        if( result ) {
            console.log('OKがクリックされました');
        }
        else {
        console.log('キャンセルがクリックされました');
            return false;
        }
    });

    //ユーザー登録確認のアラート(登録してもらうの優先)
//    $('.js-signup').on('click',function(){
//        var result = window.confirm('ユーザー登録します。よろしいですか?');
//        if( result ) {
//            console.log('OKがクリックされました');
//        }
//        else {
//        console.log('キャンセルがクリックされました');
//            return false;
//        }
//    });

    //検索機能
    var $filtered_search =$('.filtered-search');
    var $popup =  $('.popup');
    var $filtered_search_table =  $('.filtered-search-table');

    $filtered_search.on('click',function(){
        $popup.addClass('show').fadeIn();
    });

    //マイページの表示非表示
    $('.post-btn').click(function() {
      $('.mypage__panel-list2').toggle(200, 'linear');
      $('#footer').insertBefore(".push");
      $('#footer').css('button','0');
    });

    $('.favorites-btn').click(function() {
      $('.mypage__favorite-list').toggle(200, 'linear');
    });

    // 全部チェックを外す
    $('#clear').on('click',function(){ $filtered_search_table.find('input').not(':hidden').prop("checked", false);
    $('#touhoku').prop("checked", false);
    $('#kantou').prop("checked", false);
    $('#chubu').prop("checked", false);
    $('#kansai').prop("checked", false);
    $('#chaina').prop("checked", false);
    $('#shikoku').prop("checked", false);
    $('#kyushu').prop("checked", false);
    });

    $('.filtered-search').on('click',function(){
        $('.js-modal').fadeIn();
        $('.js-regist-btn').fadeOut();
        return false;
    });
    $('.js-modal-close').on('click',function(){
        $('.js-modal').fadeOut();
        $('.js-regist-btn').fadeIn(200);
        return false;
    });

    // 画像ライブプレビュー
    var $dropArea = $('.area-drop');
    var $fileInput = $('.input-file');
    $dropArea.on('dragover', function(e){
      e.stopPropagation();
      e.preventDefault();
      $(this).css('border', '3px #ccc dashed');
    });
    $dropArea.on('dragleave', function(e){
      e.stopPropagation();
      e.preventDefault();
      $(this).css('border', 'none');
    });
    $fileInput.on('change', function(e){
      $dropArea.css('border', 'none');
      var file = this.files[0],            // 2. files配列にファイルが入っています
          $img = $(this).siblings('.prev-img'), // 3. jQueryのsiblingsメソッドで兄弟のimgを取得
          fileReader = new FileReader();   // 4. ファイルを読み込むFileReaderオブジェクト

      // 5. 読み込みが完了した際のイベントハンドラ。imgのsrcにデータをセット
      fileReader.onload = function(event) {
        // 読み込んだデータをimgに設定
        $img.attr('src', event.target.result).show();
      };

      // 6. 画像読み込み
      fileReader.readAsDataURL(file);

    });
    });