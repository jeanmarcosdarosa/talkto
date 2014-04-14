<?php
require_once('./data.php');
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
  <title>CoffeeTalkTo</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Quicksand:300,400">
  <link rel="stylesheet" href="//jimmybyrum.com/css/bootstrap.min.css">
  <link rel="stylesheet" href="//jimmybyrum.com/css/font-awesome.min.css">
  <link rel="stylesheet" href="//coinledger.io/css/lib/bootstrap.extend.css">
<style>
body {
  padding: 15px 30px;
}
#logo {
  width: auto;
  height: auto;
  max-width: 200px;
}
h1 {
  font-family: 'Quicksand', sans-serif;
  margin: -3px 5px 0 0;
  font-weight: normal;
  line-height: normal;
  color: #666;
}
.tm {
  position: absolute;
  bottom: 3px;
  right: 0;
  font-size: 8px;
  color: #888;
}
select.form-control {
  position: relative;
  border: 1px solid #ccc;
  background: transparent;
  -webkit-appearance: inherit;
}
select.form-control:before {
  content: "▼";
  position: absolute;
  top: 5px;
  right: 5px;
  z-index: 10;
  display: inline-block;
  width: 2em;
  height: 2em;
}
.messages:empty {
  display: none;
}
.messages {
  border: 1px solid #ccc;
  padding: 10px;
  -webkit-border-radius: 5px;
     -moz-border-radius: 5px;
          border-radius: 5px;
  white-space: pre;
}
.messages li:not(:last-child) {
  padding-bottom: 5px;
  margin-bottom: 5px;
  border-bottom: 1px solid #ddd;
}
</style>
</head>
<body>

<?php $user = getUser($_GET); ?>
<?php $place = getPlace($_GET); ?>

<div class="container">
  <div class="row">
    <div id="content" class="col-sm-6 col-sm-offset-3">
      <div class="wrap-content" style="position: relative;">
        <h1 class="pull-left">Coffee</h1>
        <img id="logo" src="/public/images/logo.png" width="504" height="118" alt="CoffeeTalkTo" class="pull-left">
        <small class="tm">TM</small>
      </div>
      <p class="lead">CoffeeTalkTo harnesses the power of coffee-fueled humans to let you talk to any coffee shop in the Cambridge area that I've personally been to and approve of via TalkTo without having to actually talk!</p>
      <hr class="sm">
      <form class="chat">
        <div class="row">
          <div class="col-sm-6">
            <label for="place_id">To</label>
            <select id="place_id" name="place_id" class="form-control">
              <option value="49ce832ef964a5204f5a1fe3">Crema Cafe</option>
              <option value="4e9ee3031081a02e6384b390">Dwelltime</option>
              <option value="506479e8e4b01f36bfd50cf9">Simon's Too</option>
              <option value="3fd66200f964a520b1eb1ee3">1369 Coffee House (Cambridge St.)</option>
              <option value="49c031c5f964a52052551fe3">1369 Coffee House (Mass. Ave.)</option>
              <option value="4dd177b8d164679b8d479c91">Area Four</option>
              <option value="4cd1a0b17f56a1434795d5a6">Voltage</option>
            </select>
          </div>
          <div class="col-sm-6">
            <label for="person_id">From</label>
            <input type="text" class="form-control" id="person_id" name="person_id" value="<?= $user ?>">
          </div>
        </div>
        <hr class="sm">
        <ul class="list-unstyled messages col-sm-12"></ul>
        <label for="content">Message</label>
        <div class="input-group">
          <textarea class="form-control" id="content" name="content" placeholder="Enter your message here"></textarea>
          <div class="input-group-btn valign-top">
            <button type="submit" class="btn btn-primary">Send</button>
          </div>
        </div>
        <hr class="sm">
        <div class="row">
          <div class="col-sm-4">
            <label for="message_type">Message Type</label>
            <select id="message_type" name="message_type" class="form-control">
              <option value="normal">Normal</option>
              <option value="agent">Agent</option>
              <option value="notice">Notice</option>
              <option value="update">Update</option>
              <option value="auto">Auto</option>
            </select>
          </div>
          <div class="col-sm-8">
            <label for="callback_token">Callback token</label>
            <input class="form-control" id="callback_token" name="callback_token" value="http://talkto.jimmybyrum.com/callback.php">
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script id="message" class="template" type="text/x-handlebars-template">
<li id="msg-{{id}}">{{content}}</li>
</script>

<script id="alert_error" class="template" type="text/x-handlebars-template">
<div id="alert-error" class="alert alert-danger">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <p>{{error}}</p>
</div>
</script>

<script id="alert_info" class="template" type="text/x-handlebars-template">
<div id="alert-info" class="alert alert-info">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  {{error}}
</div>
</script>

<script src="//jimmybyrum.com/js/jquery.min.js"></script>
<script src="//jimmybyrum.com/js/bootstrap.min.js"></script>
<script src="//jimmybyrum.com/js/underscore.min.js"></script>
<script src="/public/js/handlebars.js"></script>
<script>
var templates = {};
$.fn.serializeObject = function() {
  var o = {};
  var a = this.serializeArray();
  $.each(a, function() {
    if (o[this.name]) {
      if (!o[this.name].push) {
        o[this.name] = [o[this.name]];
      }
      o[this.name].push(this.value || '');
    } else {
      o[this.name] = this.value || '';
    }
  });
  return o;
};
$(document).ready(function() {
  var config = <?= getConfig() ?>;
  $(".template").each(function() {
    var $tmpl = $(this);
    var id = $tmpl.attr("id");
    var html = $tmpl.html();
    var template = Handlebars.compile(html);
    templates[id] = template;
  });

  var api_root = "https://talkto.com/api/v1";
  var api_params = "?format=json&api_key=" + config.api_key;
  var apiUrl = function(path, params) {
    return api_root + (path||'') + api_params + (params||'');
  };

  var authenticate = function() {
    $.ajax({
      type: "GET",
      url: apiUrl(),
      error: function() {
        console.warn(arguments);
        // renderError({
        //   error: "Error authenticating"
        // });
      }
    });
  };
  authenticate();

  var renderError = function(error) {
    var t = templates.alert_error(error);
    $(t).prependTo("#content");
  };

  var renderMessage = function(message) {
    var t = templates.message(message);
    $(t).appendTo(".messages");
  };

  var getMessages = function() {
    var url = "/messages.php";
    url += "?person_id=" + $("#person_id").val();
    url += "&place_id=" + $("#place_id").val();
    $.ajax({
      type: "GET",
      url: url,
      success: function(messages) {
        $(".messages").empty();
        _.each(messages, function(message) {
          renderMessage(message);
        });
      },
      error: function() {
        renderError({
          error: "Error fetching messages"
        });
      }
    });
  };
  getMessages();
  var getMessagesInterval = setInterval(function() {
    getMessages();
  }, 1000 * 5);

  var addMessage = function($form, callback) {
    var data = $form.serializeObject();
    var form_data = $form.serialize();
    $.ajax({
      type: "POST",
      url: apiUrl("/message_request/"),
      data: data,
      dataType: "json",
      success: function(json) {
        _.extend(data, json);
        form_data += '&message_id=' + json.message_id;
        $.ajax({
          type: "POST",
          url: "/callback.php",
          data: form_data,
          success: function() {
            callback(data);
          },
          error: function() {
            renderError({
              error: "Error saving messages"
            });
          }
        });
      },
      error: function() {
        renderError({
          error: "Error sending messages"
        });
      }
    });
  };

  var changeDiscussion = _.debounce(function() {
    var person_id = $("#person_id").val();
    var place_id = $("#place_id").val();
    $(".messages").empty();
    getMessages();
    var url = "/";
    url += "?person_id=" + person_id;
    url += "&place_id=" + place_id;
    window.history.pushState(url, document.title, url);
  }, 300);

  $(document).on("keypress", "#person_id", function(e) {
    if (e.keyCode===13) {
      e.preventDefault();
      changeDiscussion();
    }
  });
  $(document).on("blur", "#person_id", changeDiscussion);
  $(document).on("change", "#place_id", changeDiscussion);

  $(document).on("submit", "form", function(e) {
    e.preventDefault();
    var $form = $(e.currentTarget);
    var $message = $form.find("#content");
    if ($message.val() !== "") {
      addMessage($form, function(data) {
        renderMessage(data);
        $message.val("").focus();
      });
    }
  });

  $("#content").focus();
});
</script>
</body>
</html>
