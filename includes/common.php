<?php
define ('BLUEBOOK_BACKTRACE', true);
function p($str) {

  echo '<pre>';
  print_r($str);
  echo '</pre>';
}

function dlog($msg) {

  if (!is_string($msg)) {
    ob_start();
    print_r($msg);
    $msg = ob_get_contents();
    ob_end_clean();
  }


  if (defined('BLUEBOOK_BACKTRACE') && BLUEBOOK_BACKTRACE == true) {
    $bt = debug_backtrace();

    error_log('>>>>>>>>>>>>>>> Backtrace Start >>>>>' . time());

    foreach ($bt as $k => $v) {
      if ($v['function'] != 'dlog' && $v['function'] != 'log') {
        $class = $func = false;
        if (isset($v['class'])) {
          $class = $v['class'] . '::';
        }

        if (isset($v['function'])) {
          $function = $v['function'];
        }

        if (isset($v['line'])) {
          $line = $v['line'];
        }

        error_log($class . $function . '(Line ' . $line . ')');
      }
    }
    error_log('>>>>>>>>>>>>>>>>> Backtrace END >>>>>' . time());
  }
  else {
    error_log($msg);
  }
}

?>
