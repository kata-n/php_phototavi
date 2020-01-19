var map;
var lat = document.registform.ido.value;
var lng = document.registform.keido.value;

function initialize() {
  // 地図を表示する際のオプションを設定
  var mapOptions = {
    center: new google.maps.LatLng(lat, lng),
    zoom: 16,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };

  // Mapオブジェクトに地図表示要素情報とオプション情報を渡し、インスタンス生成
  map = new google.maps.Map(document.getElementById("map_canvas"),
      mapOptions);
  
  var mark = {
    lat: parseFloat(lat), // 緯度
    lng: parseFloat(lng) // 経度
  };
  marker = new google.maps.Marker({ // マーカーの追加
  position: mark, // マーカーを立てる位置を指定
  map: map // マーカーを立てる地図を指定
  });
}

function search(){
   var place = document.getElementById('place').value;
//  console.log(place);
   var geocoder = new google.maps.Geocoder();
   // ジオコーディング　検索実行
   geocoder.geocode({"address" : place}, function(results, status) {
   if (status == google.maps.GeocoderStatus.OK) {
    var lat = results[0].geometry.location.lat();//緯度を取得
    var lng = results[0].geometry.location.lng();//経度を取得
     document.registform.ido.value = lat;
     document.registform.keido.value = lng;
        var mark = {
            lat: lat, // 緯度
            lng: lng // 経度
        };
        marker = new google.maps.Marker({ // マーカーの追加
        position: mark, // マーカーを立てる位置を指定
        map: map // マーカーを立てる地図を指定
        });
        map.setCenter(results[0].geometry.location); // 地図の中心を移動
        cnt =0;
   }
   });
}