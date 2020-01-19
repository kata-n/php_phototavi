$(function() {
    $('#touhoku').on('click', function() {
      $(".touhoku").prop('checked', this.checked);
    });
    $(".touhoku").on('click', function() {
      if ($('.prefbox :checked').length == $('.prefbox :input').length) {
        // 全てのチェックボックスにチェックが入っていたら、「全選択」 = checked
        $('#touhoku').prop('checked', true);
      } else {
        // 1つでもチェックが入っていたら、「全選択」 = checked
        $('#touhoku').prop('checked', false);
      }
    });

    $('#kantou').on('click', function() {
      $(".kantou").prop('checked', this.checked);
    });
    $(".kantou").on('click', function() {
      if ($('.prefbox :checked').length == $('.prefbox :input').length) {
        $('#kantou').prop('checked', true);
      } else {
        $('#kantou').prop('checked', false);
      }
    });
  
    $('#chubu').on('click', function() {
      $(".chubu").prop('checked', this.checked);
    });
    $(".chubu").on('click', function() {
      if ($('.prefbox :checked').length == $('.prefbox :input').length) {
        $('#chubu').prop('checked', true);
      } else {
        $('#chubu').prop('checked', false);
      }
    });
  
    $('#kansai').on('click', function() {
      $(".kansai").prop('checked', this.checked);
    });
    $(".kansai").on('click', function() {
      if ($('.prefbox :checked').length == $('.prefbox :input').length) {
        $('#kansai').prop('checked', true);
      } else {
        $('#kansai').prop('checked', false);
      }
    });

    $('#chaina').on('click', function() {
      $(".chaina").prop('checked', this.checked);
    });
    $(".chaina").on('click', function() {
      if ($('.prefbox :checked').length == $('.prefbox :input').length) {
        $('#chaina').prop('checked', true);
      } else {
        $('#chaina').prop('checked', false);
      }
    });

    $('#shikoku').on('click', function() {
      $(".shikoku").prop('checked', this.checked);
    });
    $(".shikoku").on('click', function() {
      if ($('.prefbox :checked').length == $('.prefbox :input').length) {
        $('#shikoku').prop('checked', true);
      } else {
        $('#shikoku').prop('checked', false);
      }
    });

    $('#kyushu').on('click', function() {
      $(".kyushu").prop('checked', this.checked);
    });
    $(".kyushu").on('click', function() {
      if ($('.prefbox :checked').length == $('.prefbox :input').length) {
        $('#kyushu').prop('checked', true);
      } else {
        $('#kyushu').prop('checked', false);
      }
    });
  });
