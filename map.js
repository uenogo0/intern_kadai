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


for(let i=1;i<=max2;i++){
   var marker=[];
   if(named[i] !='0'){
   var markerOptions = {
    map: map,
    position: new google.maps.LatLng(ido[i],k[i]),
    title: "title"
   }
    var markers = new google.maps.Marker(markerOptions);
    google.maps.event.addListener(markers,'click',function(event){
     alert(named[i]+"\n"+ibent[i]+"\n"+com[i]+"\n");
    });
    let newLength = marker.unshift(markers);
   }
}

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