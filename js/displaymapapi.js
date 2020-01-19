var map;
var lat = document.getElementById("ido").value;
var lng = document.getElementById("keido").value;

function initialize(){
  // 地図を表示する際のオプションを設定
  var mapOptions = {
    center: new google.maps.LatLng(lat, lng),
    zoom: 16,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
  };

  // Mapオブジェクトに地図表示要素情報とオプション情報を渡し、インスタンス生成
  map = new google.maps.Map(document.getElementById("map_canvas_display"),mapOptions);

  var mark = {
    lat: parseFloat(lat), // 緯度
    lng: parseFloat(lng) // 経度
  };
  marker = new google.maps.Marker({ // マーカーの追加
  position: mark, // マーカーを立てる位置を指定
  map: map // マーカーを立てる地図を指定
  });
}