var map;
function initMap() {
      var mapPosition = {lat: 35.170662, lng: 136.923430};
      var mapArea = document.getElementById('maps');
      var mapOptions = {
        center: mapPosition,
        zoom: 13,
        mapTypeId: google.maps.MapTypeId.ROADMAP 
      };
      var map = new google.maps.Map(mapArea, mapOptions);
//ここが間違っている
      var max = '<?php echo ($rec_max); ?>'; 
      var ido = '<?php echo ($rec_i); ?>';
      var k = '<?php echo ($rec_k); ?>';
      var ibent = '<?php echo ($rec_ibent); ?>';
      var com = '<?php echo ($rec_com); ?>';
      var name = '<?php echo ($rec_name); ?>';
/*アイコンを置く処理はこんな感じ？
    var marker=[];
    for (let i = 0; i <= max; i++) {
      mapPosition = {lat: ido[i], lng: k[i]};
      var markerOptions = {
        map: map,
        position: mapPosition,
      };
      marker[i] = new google.maps.Marker(markerOptions);
      var html = "<div>"+ibent[i]+"\n"+com[i]+"</div>";
      marker[i].openInfoWindowHtml(html);
    }
*/
//
     
      alert(max);
      var markerOptions = {
        map: map,
        position: mapPosition,
      };
      var marker = new google.maps.Marker(markerOptions);

      navigator.geolocation.getCurrentPosition(
       function (pos){
         map.setCenter(new google.maps.LatLng(pos.coords.latitude,pos.coords.longitude))
         var ido=pos.coords.latitude;
         var keido=pos.coords.longitude;
         document.cookie = 'ido='+ido;
         document.cookie = 'keido='+keido;
       },
       function (error){
        alert('地図がバグってます');
       });
}