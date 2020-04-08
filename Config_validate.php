<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $provider = $_POST["provider"];

    $imapport = 993;
    $imapserver = "";

    switch($provider){
        case "fhs":
            $imapserver = "{mail.fh-salzburg.ac.at:".$imapport."/imap/ssl/novalidate-cert}INBOX";
        break;

        case "gmx":
            $imapserver = "{imap.gmx.net:".$imapport."/imap/ssl/novalidate-cert}INBOX";
        break;

        case "gmail":
            $imapserver = "{imap.gmail.com:".$imapport."/imap/ssl/novalidate-cert}INBOX";
        break;

        case "icloud":
            $imapserver = "{imap.mail.me.com:".$imapport."/imap/ssl/novalidate-cert}INBOX";
        break;

        case "yahoo":
            $imapserver = "{imap.mail.yahoo.com:".$imapport."/imap/ssl/novalidate-cert}INBOX";
        break;

        case "outlook":
            $imapserver = "{Outlook.office365.com:".$imapport."/imap/ssl/novalidate-cert}INBOX";
        break;
    }
    
    $username = $_POST["uname"];
    $password = $_POST["psw"];

    $configfile = fopen("config.xml", "w") or die("Could not create Config-File!");
    fwrite($configfile, "E-Mail Configuration: \n");
    fwrite($configfile, "<Server>");
    fwrite($configfile, $imapserver);
    fwrite($configfile, "</Server>\n");
    fwrite($configfile, "<User>");
    fwrite($configfile, $username);
    fwrite($configfile, "</User>\n");
    fwrite($configfile, "<Password>");
    fwrite($configfile, $password);
    fwrite($configfile, "</Password>");
    fclose($configfile);

    header('Location:index.php');
}
?>