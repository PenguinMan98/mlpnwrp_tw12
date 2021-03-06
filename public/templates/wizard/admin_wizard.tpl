{* $Id: admin_wizard.tpl 50519 2014-03-27 10:16:33Z xavidp $ *}

<fieldset>
	<legend>{tr}Get Started{/tr}</legend>

	<img src="img/icons/tick.png" alt="{tr}Ok{/tr}" />{tr _0=$tiki_version}Congratulations! You now have a working instance of Tiki %0{/tr}. 
    {tr}You may <a href="tiki-index.php">start using it right away</a>, or you may configure it to better meet your needs, using one of the configuration helpers below.{/tr}
    <br>
	<div style="width:90%">
		{remarksbox type="tip" title="{tr}Tip{/tr}"}
		{tr}Mouse over the icons with a question mark to know more about the features and preferences that are new for you{/tr}.
		<a href="http://doc.tiki.org/Wizards" target="tikihelp" class="tikihelp" style="float:right" title="{tr}Help icon:{/tr} 
			{tr}You will get more information about the features and preferences whenever this icon is available and you pass your mouse over it{/tr}. 
			<br/><br/>{tr}Moreover, if you click on it, you'll be directed in a new window to the corresponding documentation page for further information on that feature or topic{/tr}.">
			<img src="img/icons/help.png" alt="" width="16" height="16" class="icon" />
		</a>
		{tr}Example: {/tr}
		{/remarksbox}
	</div>
	<br>
    <table>
        <tr>
            <td><div class="adminWizardIconleft"><img src="img/icons/large/wizard_profiles48x48.png" alt="{tr}Configuration Profiles Wizard{/tr}" title="{tr}Configuration Profiles Wizard{/tr}" /></div></td>
            <td>
                {tr}You may start by applying some of our configuration templates through the <b>Configuration Profiles Wizard</b>{/tr}. {tr}They are like the <b>Macros</b> from many computer languages{/tr}.
				<a href="http://doc.tiki.org/Profiles+Wizard" target="tikihelp" class="tikihelp" title="{tr}Configuration Profiles:{/tr} 
                {tr}Each of these provides a shrink-wrapped solution that meets most of the needs of a particular kind of community or site (Personal Blog space, Company Intranet, ...) or that extends basic setup with extra features configured for you{/tr}.
                <br><br>{tr}If you are new to Tiki administration, we recommend that you start with this approach{/tr}.
                <br><br>{tr}If the profile you selected does not quite meet your needs, you will still have the option of customizing it further with one of the approaches below{/tr}">
					<img src="img/icons/help.png" alt="" width="16" height="16" class="icon" />
				</a>
                <br>
                <input  type="submit" class="btn btn-default" name="use-default-prefs" value="{tr}Start Configuration Profiles Wizard (Macros){/tr}" />
                <br><br>
            </td>
        </tr>

        <tr>
            <td><div class="adminWizardIconleft"><img src="img/icons/large/wizard_admin48x48.png" alt="{tr}Configuration Walkthrough{/tr}" title="Configuration Walkthrough" /><br/><br/></div></td>
            <td>
                {tr}Alternatively, you may use the <b>Admin Wizard</b>{/tr}.
                {tr}This will guide you through the most common preference settings in order to customize your site{/tr}.
				<a href="http://doc.tiki.org/Admin+Wizard" target="tikihelp" class="tikihelp" title="{tr}Admin Wizard:{/tr} 
                {tr}Use this wizard if none of the <b>Configuration Profiles</b> look like a good starting point, or if you need to customize your site further{/tr}">
					<img src="img/icons/help.png" alt="" width="16" height="16" class="icon" />
				</a>
                <br>
                <input type="submit" class="btn btn-default" name="continue" value="{tr}Start Admin Wizard{/tr}" /><br><br>
            </td>
        </tr>

        <tr>
            <td><div class="adminWizardIconleft"><img src="img/icons/large/admin_panel48x48.png" alt="{tr}Admin Panel{/tr}" /></div></td>
            <td>
                {tr}Use the <b>Admin Panel</b> to manually browse through the full list of preferences{/tr}.
                <br>
                {button href="tiki-admin.php" _text="{tr}Go to the Admin Panel{/tr}"}
                <br><br>
            </td>
        </tr>
    </table>
</fieldset>

<fieldset>
<legend>{tr}Server Fitness{/tr}</legend>
	{tr _0=$tiki_version}To check if your server meets the requirements for running Tiki version %0, please visit <a href="tiki-check.php" target="_blank">Tiki Server Compatibility Check</a>{/tr}.
</fieldset>

