function check_pwd(pwd1, pwd2, memid, btn) {
  if (document.getElementById(pwd1).value ==
    document.getElementById(pwd2).value) {
    document.getElementById(memid).style.color = 'green';
    document.getElementById(memid).innerHTML = 'matching';
    document.getElementById(btn).disabled = false;
  } else {
    document.getElementById(memid).style.color = 'red';
    document.getElementById(memid).innerHTML = 'not matching';
    document.getElementById(btn).disabled = true;
  }
}


function getLocation(streetNum, streetName, city, state, zip, url)
{
    let format = "json?"
    let address = "address=";
    let delimiter = "%20";
    let keyarg = "&key="+"Give your own key";
    address += streetNum;
    address += delimiter + streetName;
    address += delimiter + city;
    address += delimiter + state;
    address += delimiter + zip;

    address = address.replace(/\s/g, delimiter);


    let requestedURL = "https://maps.googleapis.com/maps/api/geocode/";
    requestedURL += format + address + keyarg;

    let request = new XMLHttpRequest();
    request.open('GET', requestedURL);
    request.responseType = 'json';
    request.send();

    request.onload = function () {
      let locationData = request.response;
      console.log("locationData:", locationData);

      let latitude = locationData["results"][0]["geometry"]["location"]["lat"];
      let longitude = locationData["results"][0]["geometry"]["location"]["lng"];

      let response = "";
      response += latitude + ":" + longitude;
      console.log(response);

      window.location = url+"?loc=" + response;
    }
}




