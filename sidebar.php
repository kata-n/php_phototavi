  <div class="index__serch">
    <h3 class="heading serct-head">旅をさがしてみよう</h3>
    <form action="./index.php" method="post" id="contents" enctype="multipart/form-data" class="wrap">
      <h3 class="heading"><div class="filtered-search"><p class="serchtitle serct-head"><i class="fas fa-search"></i></p></div></h3>
      <div class="modal js-modal" style="display:none;">
        <div class="modal__content">
          <i class="fa fa-times icon-close js-modal-close"></i>
          <p class="serch-title">都道府県からさがす(地方をクリックするとまとめて選択できます)</p>
          <table class="filtered-search-table">
           <tbody class="prefbox">
            <tr>
              <td><label>東北<input type="checkbox" id="touhoku" style="display:none;"></label></td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="1" class="js-clear touhoku">北海道
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="2" class="js-clear touhoku">青森県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="3" class="js-clear touhoku">岩手県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="4" class="js-clear touhoku">宮城県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="5" class="js-clear touhoku">秋田県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="6" class="js-clear touhoku">山形県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="7" class="js-clear touhoku">福島県
              </label>
              </td>
            </tr>
           </tbody>
           <tbody class="prefbox">
            <tr>
              <td><label>関東<input type="checkbox" id="kantou" style="display:none;"></label></td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="8" class="js-clear kantou">茨城県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="9" class="js-clear kantou">栃木県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="10" class="js-clear kantou">群馬県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="11" class="js-clear kantou">埼玉県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="12" class="js-clear kantou">千葉県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="13" class="js-clear kantou">東京都
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="14" class="js-clear kantou">神奈川県
              </label>
              </td>
            </tr>
            </tbody>
           <tbody class="prefbox">
            <tr>
              <td><label>中部<input type="checkbox" id="chubu" style="display:none;"></label></td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="15" class="js-clear chubu">新潟県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="16" class="js-clear chubu">富山県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="17" class="js-clear chubu">石川県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="18" class="js-clear chubu">福井県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="19" class="js-clear chubu">山梨県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="20" class="js-clear chubu">長野都
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="21" class="js-clear chubu">岐阜県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="22" class="js-clear chubu">静岡県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="23" class="js-clear chubu">愛知県
              </label>
              </td>
            </tr>
            </tbody>
           <tbody class="prefbox">
            <tr>
              <td><label>関西<input type="checkbox" id="kansai" style="display:none;"></label></td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="24" class="js-clear kansai">三重県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="25" class="js-clear kansai">滋賀県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="26" class="js-clear kansai">京都府
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="27" class="js-clear kansai">大阪府
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="28" class="js-clear kansai">兵庫県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="29" class="js-clear kansai">奈良県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="30" class="js-clear kansai">和歌山県
              </label>
              </td>
            </tr>
            </tbody>
           <tbody class="prefbox">
            <tr>
              <td><label>中国<input type="checkbox" id="chaina" style="display:none;"></label></td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="31" class="js-clear chaina">鳥取県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="32" class="js-clear chaina">島根県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="33" class="js-clear chaina">岡山県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="34" class="js-clear chaina">広島県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="35" class="js-clear chaina">山口県
              </label>
              </td>
            </tbody>
           <tbody class="prefbox">
            <tr>
              <td><label>四国<input type="checkbox" id="shikoku" style="display:none;"></label></td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="36" class="js-clear shikoku">徳島県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="37" class="js-clear shikoku">香川県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="38" class="js-clear shikoku">愛媛県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="39" class="js-clear shikoku">高知県
              </label>
              </td>
            </tbody>
           <tbody class="prefbox">
            <tr>
              <td><label>九州<input type="checkbox" id="kyushu" style="display:none;"></label></td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="40" class="js-clear kyushu">福岡県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="41" class="js-clear kyushu">佐賀県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="42" class="js-clear kyushu">長崎県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="43" class="js-clear kyushu">熊本県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="44" class="js-clear kyushu">大分県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="45" class="js-clear kyushu">宮城県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="46" class="js-clear kyushu">鹿児島県
              </label>
              </td>
              <td><input type="hidden" name="pref[]">
              <label>
                <input type="checkbox" name="pref[]" value="47" class="js-clear kyushu">沖縄県
              </label>
              </td>
            </tbody>
           <tbody class="prefbox">
            <tr>
              <td><input type="hidden" name="pref[]">
              <label>
                海外<input type="checkbox" name="pref[]" value="48" class="js-clear kaigai">
              </label>
              </td>
            </tbody>
          </table>
          <p class="serch-title">特徴からさがす</p>
          <table class="filtered-search-table">
            <tbody class="emobox">
             <tr>
               <td>
                <input type="hidden" name="emotion[]">
                <label><input type="checkbox" name="emotion[]" value="1" class="js-clear">絶景</label>
               </td>
               <td>
                <input type="hidden" name="emotion[]">
                  <label><input type="checkbox" name="emotion[]" value="2">楽しい</label>
</td>
               <td>
                <input type="hidden" name="emotion[]">
                  <label><input type="checkbox" name="emotion[]" value="3">驚き</label>
</td>
               <td>
                <input type="hidden" name="emotion[]">
                  <label><input type="checkbox" name="emotion[]" value="4">神秘的</label>
               </td>
               <td>
                <input type="hidden" name="emotion[]">
                  <label><input type="checkbox" name="emotion[]" value="5">魅力的</label>
               </td>
              </tr>
            </tbody>
          </table>
          <p class="serch-title">季節からさがす</p>
          <table class="filtered-search-table">
            <tbody class="seasonbox">
              <tr>
              <td><input type="hidden" name="season[]">
              <label>
                <input type="checkbox" name="season[]" value="1" class="js-clear">春
              </label>
              </td>
              <td><input type="hidden" name="season[]">
              <label>
                <input type="checkbox" name="season[]" value="2" class="js-clear">夏
              </label>
              </td>
              <td><input type="hidden" name="season[]">
              <label>
                <input type="checkbox" name="season[]" value="3" class="js-clear">秋
              </label>
              </td>
              <td><input type="hidden" name="season[]">
              <label>
                <input type="checkbox" name="season[]" value="4" class="js-clear">冬
              </label>
              </td>
              </tr>
            </tbody>
          </table>
           <span id="clear">条件クリア</span>
           <input type="submit" value="検索">
        </div>
        <div class="modal__bg js-modal-close"></div>
      </div>
    </form>
  </div>
