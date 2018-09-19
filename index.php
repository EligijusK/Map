<?php
 include_once('includes/dbh.inc.php');
 include_once('header.php')
?>

    <section class="main-container">
        <?php
        if(isset($_SESSION['u_email']))
        {
            echo('
            <div id="devices">
            <div id="example" style="visibility: hidden"></div>
                <div class="add">
                     <form method="POST">
                    <lable>Id:</lable>
                    <input name="id">
                    <span id="tooltiptext" style="visibility: hidden">Coordinates example: "51.507363, -0.127748"</span>
                    <lable>Coordinates:</lable>
                    <input onmouseover="visible()" onmouseout="hiddenout()" name="cordinates">
                    <lable>Place: </lable>
                    <select name="cars">
                    <option value="Home" >Home</option>
                    <option value="Work">Work</option>
                    </select>
                    <button type="submit" name="Submit">Send</button>
                    </form>
                </div>
                <div id="list">
                </div>
                <div id="result">
                <div id="in_kilo"></div>
                </div>
            </div>
            <div id="map" class="mapedit"></div>
            ');
        }
        else
        {
            echo('
            <form method="POST">
            <lable>Id:</lable>
            <input name="id">
            <span id="tooltiptext" style="visibility: hidden">Coordinates example: "51.507363, -0.127748"</span>
            <lable>Cordinates:</lable>
            <input id="coordinates" name="cordinates" onmouseover="visible()" onmouseout="hiddenout()">
            <lable>Place: </lable>
            <select name="cars">
            <option value="Home" >Home</option>
            <option value="Work">Work</option>
            </select>
            <button type="submit" name="Submit">Send</button>
            </form>
            ');
        }
        ?>
    </section>
<?php

     include_once('footer.php');

if(isset($_POST['Submit']) === true)
{
if(((!isset($_POST['id']) || trim($_POST['id']) != '') && (preg_match("/^\d+\w*$/", $_POST['id']) || preg_match("/^\w+\d*$/", $_POST['id']))) && ((!isset($_POST['cordinates']) || trim($_POST['cordinates']) != '') && preg_match("/^\-*\d+\.\d+\,\s\-*\d+\.\d+$/", $_POST["cordinates"]) ))
{
    $data = "INSERT INTO `maps` (Device_Id, Coordinates, Choise, created_at, updated_at) VALUES ('".$_POST['id']."', '".$_POST['cordinates']."', '".$_POST['cars']."', NOW(), NOW() )";
    if($sql->query($data) === true)
    {
        echo('<script>alert("record added to database")</script>');
    }
    else
    {        echo("<script>alert('record can not be added to database')</script>");
    }
}
else
{
    echo('<script>alert("pleas fill all fields")</script>');
}
}


if(isset($_SESSION['u_email']))
{
    echo('

<script>

  var km = 0; // length between two cordinates
  function getDistance(origin, destination, id1, id2)
  {
     //Find the distance
     var distanceService = new google.maps.DistanceMatrixService();
     distanceService.getDistanceMatrix({
        origins: [origin],
        destinations: [destination],
        travelMode: google.maps.TravelMode.DRIVING,
        unitSystem: google.maps.UnitSystem.METRIC,
        durationInTraffic: true,
        avoidHighways: false,
        avoidTolls: false
    },
    function (response, status) {
        if (status !== google.maps.DistanceMatrixStatus.OK) {
            console.log("Error:", status);
        } else {
            if(response.rows[0].elements[0].distance.value > km) // find max length
            {
            km = response.rows[0].elements[0].distance.value;
            document.getElementById("in_kilo").innerHTML = "Distance: "+km / 1000+" km between "+id1+" and "+id2;
            }

        }
    });
  }

 var markers = [];
function myMap() { // make map

        var mapOptions = {
            center: new google.maps.LatLng(55.303395, 23.990905),
            zoom: 7,
            mapTypeId: google.maps.MapTypeId.HYBRID
        };

        var infowindow = new google.maps.InfoWindow({ // create info window when clicks on pointer

        });

        var pos = {lat: 51.5, lng: -0.12};  // center of the map
        var map = new google.maps.Map(document.getElementById("map"), mapOptions);

        function updateMap() // created function for automaticly update because of database. If in database apears new record website must reload.
        {
    function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
          markers[i].setMap(map);
        }
      }

      // Removes the markers from the map, but keeps them in the array.
      function clearMarkers() {
        setMapOnAll(null);
      }

      // Shows any markers currently in the array.
      function showMarkers() {
        setMapOnAll(map);
      }

      // Deletes all markers in the array by removing references to them.
      function deleteMarkers() {
        clearMarkers();
        markers = [];
      }

            km = 0;
            $.ajax({
                type: "POST",
                url: "includes/pointer.inc.php",
                dataType: "json",
                cache: false,
                success: function(result) {
                    var locations = result;
                    if(locations.length > markers.length)
                    {
                    deleteMarkers();
                    markers.splice(0, locations.length);
                    }
                    for (i = 0; i < locations.length; i++) {
                        for(a = 0; a < locations.length; a++)
                        {
                            if(locations.length > 1 && locations[i][2] != locations[a][2] && locations[i][3] != locations[a][3])
                            {
                            getDistance(locations[i][2]+","+locations[i][3],  locations[a][2]+","+locations[a][3], locations[i][0], locations[a][0]); // method to calculate max distance between two coordinates
                            }
                            else if(locations.length <= 1)
                            {
                                document.getElementById("in_kilo").innerHTML = "";
                            }

                        }

                        var marker = new google.maps.Marker({ // create new marker with database coordinates
                        position: new google.maps.LatLng(locations[i][2], locations[i][3]),
                        map: map
                        });
                        markers.push(marker); // add marker to array if needs to coregate
                            google.maps.event.addListener(marker, "click", (function(marker, i) { // if pointer is clicked show data in pop up box
                            return function() {

                                $.ajax({ // request for changing coordinates to address
                                    type: "GET",
                                    url: "https://nominatim.openstreetmap.org/reverse?format=xml&lat="+locations[i][2]+"&lon="+locations[i][3]+"&zoom=18&addressdetails=1",
                                    dataType: "xml",
                                    success: function(adress)
                                    { // if sucess add data to info box
                                    var info = $(adress).find("result").text();
                                    var res = info.split(",");
                                    infowindow.setContent("<div>Device id: "+locations[i][0]+"</div>"+"<div>Place: "+locations[i][1]+"</div>"+"<div>Adress: "+res[0]+","+res[1]+","+res[2]+",</br>"+res[3]+","+res[4]+","+res[5]+"</div>");
                                    infowindow.open(map, marker);
                                    }
                                });

                            }
                            })(marker, i));

                    }
                }

            });
        }
        updateMap();
        setInterval(updateMap, 3000);
}
function updateList()
{
     $.ajax({    //create an ajax request to list.inc.php
     type: "POST",
     url: "includes/list.inc.php",
     dataType: "html",   //expect html to be returned
     success: function(response){
     $("#list").html(response); // return list from database
     }
     });
 }
 
 updateList();
setInterval(updateList, 3000);

</script> ');
    echo('<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCVh6B0capYQNn0RvjAZ2BtWP0ck-iaw8s&callback=myMap"></script>');
}
echo('<script>
function visible() // functions for make tip visable or hidden
{
document.getElementById("tooltiptext").style.visibility = "visible";
}

function hiddenout()
{
document.getElementById("tooltiptext").style.visibility = "hidden";
}

</script>');
?>
