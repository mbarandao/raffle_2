<!DOCTYPE html>
<?php
// ini_set('display_errors', '1');
require_once "libs/Mobile-Detect-master/Mobile_Detect.php";
$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
$scriptVersion = $detect->getScriptVersion();
?>
<!--[if IE 8]> 				 <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->

  <head>
    <meta charset="UTF-8">
<?php
      if($detect->isMobile())
      {
    ?>
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <?php
      }
      else {
    ?>
        <meta name="viewport" content="width=device-width" />
    <?php
      }
    ?>
      <link rel="shortcut icon" type="image/x-icon" href="http://pongmobile.com/assets/logo-0da1fb3ef959c1e6c4fae1a3a9d20e950ce76e06dbcec75c36a5cd964e1590a1.png" />
      <link rel="mask-icon" type="" href="http://pongmobile.com/assets/logo-0da1fb3ef959c1e6c4fae1a3a9d20e950ce76e06dbcec75c36a5cd964e1590a1.png" color="#111" />
      <link rel="stylesheet" href="css/normalize.css" />
      <link rel="stylesheet" href="css/foundation.css" />
      <script src="js/jquery.min.js"></script>
      <script src="js/foundation.min.js"></script>
      <script src="js/vendor/stop-execution-on-timeout.js"></script>
      <script type='text/javascript' charset='utf-8'>
      // Hides mobile browser's address bar when page is done loading.
      window.addEventListener('load', function(e) {
        setTimeout(function() { window.scrollTo(0, 1); }, 1);
      }, false);
      </script>

    <title>PongMobile Raffle System</title>

    <script>
      document.write('<script src=' +
      ('__proto__' in {} ? 'js/vendor/zepto' : 'js/vendor/jquery') +
      '.js><\/script>')
    </script>

    <script>$(document).foundation();</script>

    <script>
      window.console = window.console || function(t) {};
      if (document.location.search.match(/type=embed/gi)) {
        window.parent.postMessage("resize", "*");
      }
    </script>

  </head>

  <body translate="no" onload="reset();">

    <div class="full-head nav-container">
        <div class="row">
          <h1 class="title">
            PongMobile Raffle System
          </h1>

          <div class="large-12 columns nav">
            <ul class="button-group even-4">
              <li><a href="/" class="alert button" id="reset" onclick="return confirm('Will delete any new names added. Are you sure?')">Reset</a></li>
              <li><button class="button" id="list" onclick="namelist()">Player List</button></li>
              <li><button class="button" id="save" onclick="save();">Update</button></li>
              <li><button class="success button" id="go" onclick="go();">Let's Go!</button></li>
            </ul>
          </div>
        </div>
      </div>
    <div class="row">
      <div class="large-12 columns" >
          <h2 id="headline">Excited? Let's see who is the Lucky player!</h2>
          <div class="go" align=center>
            <div class="round-button">
              <div class="round-button-circle"><a href="javascript:go();" class="round-button">RUN RAFFLE</a></div>
            </div>
          </div>

        <div class="start-page">
          <div class="start" align=center>
            <div class="round-button">
              <div class="round-button-circle"><a href="javascript:go();" class="round-button">RUN RAFFLE</a></div>
            </div>
          </div>
        </div>

        <div id="popdown">
          <div id="names" class="inbox"><textarea name="namesbox" id="namesbox"></textarea></div>
        </div

          <div id="values"></div>

          <div class="name-li" >
            <ol id="raffle"></ol>
          </div>

          <div class="congrats"></div>

          <br clear="all" />
        </div>
      </div>
    </div>
    <div class="sound">
      <audio id="epicSound">
        <source src="sounds/ticker-sound.ogg" type="audio/ogg">
        <source src="sounds/ticker-sound.mp3" type="audio/mp3">
      </audio>

      <audio id="winnerSound">
        <source src="sounds/andTheWinnerIs.ogg" type="audio/ogg">
        <source src="sounds/andTheWinnerIs.mp3" type="audio/mp3">
      </audio>
    </div>

    <script >
        function cleanArray(actual) {
          var newArray = new Array();
          for (var i = 0; i < actual.length; i++) {
            if (actual[i]) {
              newArray.push(actual[i]);
            }
          }
          return newArray;
        }

      //for a more true random pick, we shuffle the array to randomly place the each value in the array at different location.
      //prior to selecting at random
      function shuffle(array) {
        var currentIndex = array.length, temporaryValue, randomIndex;
        // While there remain elements to shuffle...
        while (0 !== currentIndex) {
          // Pick a remaining element...
          randomIndex = Math.floor(Math.random() * currentIndex);
          currentIndex -= 1;
          // And swap it with the current element.
          temporaryValue = array[currentIndex];
          array[currentIndex] = array[randomIndex];
          array[randomIndex] = temporaryValue;
        }
        return array;
      }

      function playerNames(){
          var names = new Array("Linda","Charlene","William","Parker", "Kinley","Geovanni","Selena", "Arnd","Callie");
        return names;
      }
      var text;
      var players = shuffle(cleanArray(playerNames()));

      function reset(){
        // re-enable go button
        setTimeout("$('#go').removeAttr('disabled')",11005);
        //$('#save').attr('disabled','disabled');
        var namesbreak = "";
        if(gup('players') != ""){
          var players = gup('players');
          namesbreak = players.replace(/101/g,'\n');
          namesbreak = namesbreak.replace(/%20/g,' ');
        }

        else{
          var players = playerNames();
          for(var i in players){
            name = players[i];
            if (name == "" || typeof(name) == undefined){}else{
              namesbreak = namesbreak + name + "\n";
            }
          }
        }
        $("#namesbox").val(shuffle(namesbreak));
      }

      function cleanNames(para){
        para = para.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
        var regexS = "[\\?&]"+para+"=([^&#]*)";
        var regex = new RegExp( regexS );
        var results = regex.exec( window.location.href );
        if( results == null )
        return "";
        else
        return results[1];
      }

      function gup(param){
        para = param.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
        var regexS = "[\\?&]"+para+"=([^&#]*)";
        var regex = new RegExp( regexS );
        var results = regex.exec( window.location.href );
        if( results == null )
        return "";
        else
        return results[1];
      }

      function namelist(){
        $('#namesbox').removeAttr('disabled','disabled');
        $('#headline').text('Modify player list and click "Update"!');
        $("#popdown").show();
        $('.go').fadeOut("fast");
        $("#values").hide();
        $("#names").show();
        $('body').css({"overflow-y": "visible"});
      }

      // does the actual animation
      function save(){
        $("#popdown").show();
        $("#values").hide();
        $(".name-li").hide();
        $("div").remove("#result1");
        savenames = $("#namesbox").val();
        savenames = savenames.replace(/\n\r?/g, '101');
        $('#headline').fadeOut();
        $('#headline').text('Nicely done! The player list is updated.').fadeIn();
        $("#names").hide();
        $(".start-page").fadeIn("slow");
        $(".start").fadeIn("slow");
        $('#namesbox').attr('disabled','disabled');
      }


      function go() {
        $('body').css({"overflow-y": "hidden"});
        $('#go').attr('disabled','disabled');
        $('.go').fadeOut("fast");
        $("#epicSound")[0].play();
        $('.start').fadeOut("fast");
        $(".name-li").show();
        $('.nav').fadeOut("slow");
        $('#headline').text('Processing...');
        // $('#list').attr('disabled','disabled');
        // $('#save').attr('disabled','disabled');
        $('#headline').slideUp("fast");
        $('#namesbox').slideDown("fast");

        var count = 1;
        count = 1;
        $("div").remove("#result1");
        names = $("#namesbox").val();

        if(document.all) { // IE
          names=names.split("\n");
          names = cleanArray(names);

        }
        else { //Mozilla
          names=names.split("\n");
          names = cleanArray(names);
        }

        $("#values").show();
        $(".name").show();
        $("#popdown").hide();
        $("div").remove(".name");
        $("div").remove(".extra");
        $("#playback").html("");
        newtop = names.length * 200 * -1;
        $('#values').css({top: + newtop});

        for(var i in names){
          if (names[i] == "" || typeof(names[i]) == undefined){
            count = count-1;
          } else {
            name = names[i];
            $('#values').append('<div id=result'+count+' class=name>'+name+'</div>');
          }
          count = count+1;
        }
        for(var i in names){
          if (names[i] == "" || typeof(names[i]) == undefined){}else{
            name = names[i];
            $('#values').append('<div class=name>'+name+'</div>');
          }
          count = count+1;
        }

        text = $('#result1').text()
        $('#values').animate({top: '+176'},5500);


      // Initializes and runs the raffle.
        var jForm = $( "form:first" );
        var jSelect = jForm.find( ":input[ name = 'count' ]" );
        var jRaffle = $( "#raffle" );

        // Clear the raffle list.
        jRaffle.empty();
        var names = shuffle(cleanArray(names)); // from array function above

        for(var i=0;i<names.length;i++){if (window.CP.shouldStopExecution(1)){break;}
          var item = names[i];
          jRaffle.append( "<li><div>" + item + "</div></li>" );
        }
      window.CP.exitedLoop(1);

      // Find the "on" element.
      var jCurrentLI = jRaffle.find( "li:first" ).addClass( "on" );

      // Get base pause time.
      var intPause = 20;

      // Get the time to wait before chaning the pause time.
      var intDelay = (5500 + (Math.random() * 1000));

      // Define the ticker.
      var Ticker = function() {
        var jNextLI = jCurrentLI.next( "li" );

        // Check to see if there is a next LI.
        if (!jNextLI.length){
          jNextLI = jRaffle.find( "li:first" );
        }

        // Turn off the current list.
        jCurrentLI.removeClass( "on" );

        // Turn on next list.
        jNextLI.addClass( "on" );

        // Store the new LI in the current LI (for next iteration).
        jCurrentLI = jNextLI;
        // Check to see if we should start changing the pause duration.
        if (intDelay > 0){
          // Substract from the delay.
          intDelay -= intPause;
        } else {
          // Change the pause.
          intPause *= (1 + (Math.random() * .2));
        }

        // Check to see the lenght of the pause. Once it gets over a certain wait
        // time, we are done going through the names, let's picking the winner and highlight he or she. let's disable the losers
        if (intPause >= 300){ // run ticker for at least 16 seconds and than select a winner
          if(!$("#epicSound").paused) {
            var newVolume = $("#epicSound")[0].volume - 0.8;
            if(newVolume >= 0){
              $("#epicSound")[0].volume = newVolume;
              $("#winnerSound")[0].play();
            }
          }
          $('li[class="on"]').addClass("winner");
          $('#raffle').removeClass("play");
          $('.winner').css('width', '+=100px' );// to assure that the winner name is fully shown in the div, we add 100px to winning on the fly
          $('#olraffle li:not([class]), li[class=""]').addClass("off");// find the losers and give them class
          $('li[class="off"]').delay(1000).animate({"opacity":.10}, "slow");//remove the losers
          $('.winner').delay(500).animate({height: "+=50px", width: "+=80", "font-size":"65", "padding-bottom": "20px", "margin-right": "20px"}, 'slow');
          $('#headline').text('The Lucky Winner is ....');
          var winner =  $(".winner").find('div').first().text();
          $('.winner').delay(2000).queue(function(next){$(this).append('<div class="extra"><a class="small alert button"  href="#" onClick="namelist();">Want to remove from list?</a></div><div id="w-name">'+winner+'</div>').fadeIn("slow")});
          $('.nav').delay(3000).slideDown("slow");//bring back the nav
          $('#headline').slideDown();
          // $('.congrats').text('Congratulations!');
          // $('.congrats').fadeIn('slow');
        }else {
          // Not done yet, call again shortly.
          setTimeout( Ticker, intPause );
          // make it stand out
          setTimeout("standout(text)",5500);
        }
      }
      Ticker();
    }

      function standout(text){
        $('#result1').removeClass('name');
        $('.name').animate({opacity: .25});
        $('#result1').animate({height: '+=80px'});
        $('#result1').append('<div class="extra"><a class="small alert button" href="#" onClick="namelist();">Remove from list</a></div>');
        $('#go').removeAttr('disabled','disabled');
        // $('#list').removeAttr('disabled','disabled');
        // $('#save').removeAttr('disabled','disabled');
        // $('#headline').text('Found the Winner!');
        // $('#headline').slideDown();
      }
      function removevictim(){ //NOT IN USE
        // Removing victim from array and UI
        // Rewriting namesbox contents
        var nameupdated = "";
        for(var i in players){
          name = players[i];
          if (name == "" || name == text || typeof(name) == undefined){
          }else{
            var winnerToRemove = $(".winner").find('div').first().text();//$(".w-name").find('div').first().text();
            nameupdated = (nameupdated + "\n" + name).replace(winnerToRemove, '');
          }
        }
        $('#namesbox').val("");
        $('#namesbox').val(nameupdated);
        save();
        $('#result1').html("Removed");
        $('#result1').fadeOut(1000, function(){
          $("div").remove("#result1");
        });
        $("div").remove(".name");
        $("div").remove(".extra");
        $('#headline').text('OK, done! Let\'s see who is next? Just run the raffle again.');
      }
    </script>
  </body>
</html>
