Captive Portal module by @xtr4nge
<br>
This module creates a basic captive portal to allow|deny internet access, injects code and captures browser details. 

<br><br>
<b>[USERS]</b>
<br>
List of connected users. Internet Access can be granted or revoked on this tab.

<br><br>
<b>[OPTIONS]</b>
<br>
<b>Mode</b>: Close it denies the access by default. Open it denies the access when the device is connected. (AP module v1.5 is required for Open mode) 
<br>
<b>Portal URL</b>: Domain that will be used for the captive portal. This option should be combined with FruityDNS.
<br>
<b>Portal Name</b>: Name of the portal being displayed in TITLE and in BODY.
<br>
<b>Show Policy Page</b>: If enabled, it will display the policy page to the user.
<br>
<b>Show Welcome Page</b>: If enabled, it will display the welcome page to the user.
<br>
<b>Recon (inject recon)</b>: If enabled, it will inject code in the Captive Portal to capture device details. The captured details are displayed in tab DB.
<br>
<b>Inject (inject code)</b>: If enabled, it will inject the code (tab INJECT) in the Captive Portal.
<br>
<b>Validate User/Email</b>: If enabled, it will validate User/Email (User option)
<br>
<b>Validate Pass</b>: If enabled, it will validate the password (Pass option)
<br>
<b>portal_default</b>: Captive portal template [default]
<br>
<b>portal_hotel</b>: Captive portal template [hotel]
<br>
<b>http2https</b>: If enabled, after validate the device, it will be redirected to HTTPS.
<br>
<b>Add | Remove www (oposite)</b>: If enabled, it will remove (or add) "www" from/to the requested domain. 
<br>
<b>Timestamp (add)</b>: If enabled, it will add a timestamp as a variable into the requested domain.
<br>
<b>Force Redirect</b>: If enabled, after validate the device, it will be redirected to this URL.

<br><br>
<b>[INJECT]</b>
<br>
This is the code that is injected if "inject" option is enabaled.

<br><br>
<b>[DB]</b>
<br>
List of captured data from users including IP, Macaddress, Browser details and plugins.