user_insert = "
	INSERT INTO !PREFIX!users (
		name, username, password
	) VALUES (
		?, ?, ?
	)
"

user_permission_add = "
	INSERT INTO !PREFIX!user_permissions (
		id, permission, filter
	) VALUES (
		?, ?, ?
	)
"