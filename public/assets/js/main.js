String.prototype.ucwords = function() {
    return this.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase();
    });
}

function getWeatherForcastFn(weatherFor = "Current Weather"){
  $.ajax({
      url:base_url + 'get-weather-forcast',
      type:"POST",
      data:{
        weatherFor:weatherFor,
        location:$("#location").val(),
        lat:$("#location").data('lat'),
        lon:$("#location").data('lon')
      },
      dataType:"json",
      beforeSend: function(){
        $("#spinner").show();
      },
      success: function(response){
       $("#spinner").hide();
       try {
        //
       }
       catch(err) {
         console.log(response);
       }
       if(response.status=="success")
       {
         if(weatherFor == "Current Weather"){
          if(response.data.weather[0].icon){
            var img = '<img src="http://openweathermap.org/img/w/' + response.data.weather[0].icon + '.png" width="50" height="50" class="me-1">';
          } else{
            var img = '';
          }
            $("#weather-description").empty().html(img + response.data.weather[0].description.ucwords());
            $("#current-temperature").empty().html(response.data.main.temp + '&#8451;');
            $("#feels-like").empty().html(response.data.main.feels_like + '&#8451;');
            $("#humidity").empty().html(response.data.main.humidity + '%');
            $("#current-weather-section").show();
            $("#next-24-hours-section").hide();
            $("#next-7-days-section").hide();
         } else if(weatherFor == "Next 24 Hours"){
            var html = '';
            $.each(response.data.list, function(key, val) {
              if(val.weather[0].icon){
                var img = '<img src="http://openweathermap.org/img/w/' + val.weather[0].icon + '.png" width="50" height="50" class="me-1">';
              } else{
                var img = '';
              }
              html += '<tr><td class="text-center">' + moment(new Date(val.dt_txt)).format('ddd, MMM DD YYYY hh:mm A') + '</td><td class="text-center">' + img + val.weather[0].main + '</td><td class="text-center">' + val.main.temp + '&#8451;</td></tr>';
            });
            $('#next-24-hours-table-tbody').empty().html(html);
            $("#current-weather-section").hide();
            $("#next-24-hours-section").show();
            $("#next-7-days-section").hide();
         } else if(weatherFor == "Next 7 Days"){
            var html = '';
            $.each(response.data.list, function(key, val) {
              if(val.weather[0].icon){
                var img = '<img src="http://openweathermap.org/img/w/' + val.weather[0].icon + '.png" width="50" height="50" class="me-1">';
              } else{
                var img = '';
              }
              html += '<tr><td class="text-center">'+ moment(new Date(val.dt_txt)).format('ddd, MMM DD YYYY hh:mm A') + '</td><td class="text-center">' + img + val.weather[0].main + '</td><td class="text-center">' + val.main.temp + '&#8451;</td></tr>';
            });
            $('#next-7-days-table-tbody').empty().html(html);
            $("#current-weather-section").hide();
            $("#next-24-hours-section").hide();
            $("#next-7-days-section").show();
         }
       }
       else
       {
          console.log('Something went wrong!');
       }
    },
    error: function (request, status, error) {
      $("#spinner").hide();
      console.log('Something went wrong!');
    }
    });
}


$(document).ready(function() {
  var smallLoader = basePath + '/assets/images/small-loader.gif';
    $("#location").keyup(function() {
        $.ajax({
            type: "POST",
            url: base_url + 'get-auto-suggestions',
            data:{
              keyword: $(this).val()
            },
            dataType:"json",
            beforeSend: function() {
                $("#location").css("background", "#FFF url(" + smallLoader + ") 98% no-repeat");
            },
            success: function(data) {
                var html = '';
                var listItemsArr = [];
                if(data.data.length > 0){
                  html += '<ul id="location-list">';
                  $.map(data.data, function (item) {
                    var keyword = '';
                    if(item.name){
                      keyword +=item.name;
                    }
                    if(item.state){
                      keyword += ', ' + item.state;
                    }
                    if(item.country){
                      keyword += ', ' + item.country;
                    }
                    if(keyword){
                      listItemsArr.push(keyword);
                      html += '<li class="location-list-item" data-keyword="'+keyword+'" data-name="'+ item.name +'" data-state="'+item.state+'" data-country="'+item.country+'" data-lat="'+item.lat+'" data-lon="'+item.lon+'">' + keyword + '</li>';
                    }
                  });
                  html += '</ul>';
                }
                if(listItemsArr.length > 0){
                  $("#suggesstion-box").css({ "margin-bottom" : "235px" });
                  $("#suggesstion-box").html(html);
                } else{
                  $("#suggesstion-box").css({ "margin-bottom" : "30px" });
                  $("#suggesstion-box").empty();
                }
                $("#location").css("background", "#FFF");
            }
        });
    });
});

$(document).on('click','.location-list-item', function(e){
  $("#location").val($(this).data('keyword'));
  $('#location').data('lat', $(this).data('lat'));
  $('#location').data('lon', $(this).data('lon'));
  $("#suggesstion-box").css({ "margin-bottom" : "30px" });
  $("#suggesstion-box").empty();
  getWeatherForcastFn($('input[name="weather_for"]:checked').val());
});

$(document).on('change','.weather_for', function(e){
  $("#current-weather-section").hide();
  $("#next-24-hours-section").hide();
  $("#next-7-days-section").hide();
  if($("#location").val() != ""){
    getWeatherForcastFn($('input[name="weather_for"]:checked').val());
  }
});