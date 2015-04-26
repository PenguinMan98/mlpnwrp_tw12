{* $Id: unsuccessful_logins.tpl 49140 2013-12-17 17:32:15Z jonnybradley $ *}
{$msg}
{tr}Please visit this link before login again:{/tr}
{$mail_machine}?user={$user|escape:'url'}&pass={$mail_apass}

{tr}Last attempt:{/tr} {tr}IP:{/tr} {$mail_ip}, {tr}User:{/tr} {$user}
