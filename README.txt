- mini HowTo

	1. 	Install the Extension with Extension Manager
	
	2. 	Set Warning Email Adress.
		If a User try to switch to a nonallowed User, you got a
		warning message to this Email
		(if there is no email, the [BE][warning_email_addr] is taken)
	
	3. 	In USER TS config you can use following to "filter" 
		the User List for every User Or User Group.
			
			// comma-separated list with UIDs of usergroups
			// it's not possible to switch to Users in this groups
			-	tx_switch_beuser.disallowedGroups = 2,3
			
			// comma-separated list with UIDs of Single users
			- 	tx_switch_beuser.disallowedUsers = 2,3
			
			// the switched User can't edit the fields
			- 	tx_switch_beuser.readOnly = 1
			