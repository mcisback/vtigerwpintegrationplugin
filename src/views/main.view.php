<h1>Welcome to <?php echo PLUGIN_NAME; ?></h1>

<?php

?>

<p>
  Your Facebook Webhook URL: <b>{{fbWebhookUrl}}</b>
</p>

<?php

require_once PLUGIN_PATH.'/config.php';

echo "<form id='submitForm' action='{$_SERVER['PHP_SELF']}?page={$_GET['page']}' method='POST' enctype='application/x-www-form-urlencoded'>\n";

foreach ($fields as $key => $options) {

  echo "<div class='form-group'>\n";

  $label = $key;

  if(isset($options['label']) && !empty($options['label'])) {
    $label = $options['label'];
  }

  echo "<label for='{$key}' style='font-weight: bold;'>" . str_replace('_', ' ', $label) . "</label>\n";

  if(isset($options['extra_before'])) {
    echo $options['extra_before'] . "\n";
  }

  if($options['type'] === 'checkbox') {

    // echo 'Var Dump: ';
    // var_dump($options['default']);

    echo "<input type='hidden' name='$key' value='{$options['default']}'>\n";
    echo "<input type='{$options['type']}' id='$key' class='form-control' onclick=\"console.log(this, this.previousSibling.previousSibling); this.previousSibling.previousSibling.value = this.checked ? 1 : 0;\"";

    if(isset($options['default'])){

      if($options['default'] === 1 || $options['default'] === '1') {
        echo " checked";
      }

    }

    echo ">\n";

  } else {

    echo "<input type='{$options['type']}' id='$key' value='{$options['default']}' name='$key' class='form-control'>\n";

  }

  echo "</div>\n";

}

echo "</form>\n";


ViewHelper::includeWithVariables(
	PLUGIN_PATH.'views/partials/stickybar.partial.php',
	array(),
	PRINT_OUTPUT,
	array(
		"{{sbarButtonLabel}}" => 'Save'
	)
);

?>

<script>

  var $ = $ ? $ : jQuery;

  $( document ).ready(function() {

    $('#sbarButton').click(function(e){

      $('#submitForm').submit();

    });

  });

  function insertAtCursor(myField, myValue) {
    //IE support
    if (document.selection) {
        myField.focus();
        sel = document.selection.createRange();
        sel.text = myValue;
    }
    //MOZILLA and others
    else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        myField.value = myField.value.substring(0, startPos)
            + myValue
            + myField.value.substring(endPos, myField.value.length);
    } else {
        myField.value += myValue;
    }
  }

  function addText(buttonEl, inputId) {

    let inputEl = document.getElementById(inputId);

    console.log('addText, buttonEl: ', buttonEl);
    console.log('addText, buttonEl.nodeValue: ', buttonEl.innerHTML);
    console.log('addText, inputEl: ', inputEl);

    insertAtCursor(inputEl, buttonEl.innerHTML);

  }

</script>
