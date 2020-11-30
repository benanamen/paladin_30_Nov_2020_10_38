	CREATE TABLE !PREFIX!user_permissions (
		id BIGINT,
		permission VARCHAR(127),
		filter TINYINT DEFAULT -1,
		INDEX (id, permission)
	)
