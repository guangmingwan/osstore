<?php
extension_check(array( 

 'curl',

 'dom', 

 'gd', 

 'hash',

 'iconv',

 'mcrypt',

 'pcre', 

 'pdo', 

 'pdo_mysql', 

 'simplexml'

));

function extension_check($extensions) {

 $fail = '';

 $pass = '';

 

 if(version_compare(phpversion(), '5.2.0', '<')) {

  $fail .= '<li>You need<b> PHP 5.2.0</b> (or greater)</li>';

 }

 else {

  $pass .='<li>You have<b> PHP 5.2.0</b> (or greater)</li>';

 }

 if(!ini_get('safe_mode')) {

  $pass .='<li>Safe Mode is <b>off</b></li>';

  preg_match('/[0-9]/.[0-9]+/.[0-9]+/', shell_exec('mysql -V'), $version);

  

  if(version_compare($version[0], '4.1.20', '<')) {

   $fail .= '<li>You need<b> MySQL 4.1.20</b> (or greater)</li>';

  }

  else {

   $pass .='<li>You have<b> MySQL 4.1.20</b> (or greater)</li>';

  }

 }

 else { $fail .= '<li>Safe Mode is <b>on</b></li>';  }

 foreach($extensions as $extension) {

  if(!extension_loaded($extension)) {

   $fail .= '<li> You are missing the <b>'.$extension.'</b> extension</li>';

  }

  else{ $pass .= '<li>You have the <b>'.$extension.'</b> extension</li>';

  }

 }

 

 if($fail) {

  echo '<p><b>Your server does not meet the following requirements in order to install Magento.</b>';

  echo '<br>The following requirements failed, please contact your hosting provider in order to receive assistance with meeting the system requirements for Magento:';

  echo '<ul>'.$fail.'</ul></p>';

  echo 'The following requirements were successfully met:';

  echo '<ul>'.$pass.'</ul>';

 } else {

  echo '<p><b>Congratulations!</b> Your server meets the requirements for Magento.</p>';

  echo '<ul>'.$pass.'</ul>';

 }

}


