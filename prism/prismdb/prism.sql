CREATE TABLE "dashboard" (
	 "dash_id" text NOT NULL,
	 "user_id" text NOT NULL,
	 "graph_ids" text,
	 "dash_info" TEXT NOT NULL,
	 "share_link" text,
	 "create_time" text NOT NULL,
	 "update_time" text NOT NULL,
	 "status" integer NOT NULL default 1,
	 "dash_brief" TEXT,
	PRIMARY KEY("dash_id")
);

CREATE TABLE "dblink" (
	 "db_id" text NOT NULL,
	 "user_id" text(10,0),
	 "db_type" integer(3,0) NOT NULL ON CONFLICT ROLLBACK DEFAULT 1,
	 "link_info" text NOT NULL ON CONFLICT IGNORE,
	 "create_time" text NOT NULL,
	 "update_time" text NOT NULL,
	 "status" integer NOT NULL DEFAULT 1,
	 PRIMARY KEY("db_id")
);

CREATE TABLE "graph" (
	 "graph_id" text NOT NULL,
	 "db_linkid" text NOT NULL,
	 "user_id" text NOT NULL,
	 "graph_type" integer NOT NULL,
	 "graph_info" TEXT NOT NULL,
	 "create_time" TEXT NOT NULL,
	 "update_time" TEXT NOT NULL,
	 "status" integer NOT NULL default 1,
	 "share_link" TEXT,
	 "graph_brief" TEXT,
	 PRIMARY KEY("graph_id")
);

CREATE TABLE "ids" (
	 "id" text(30,0) NOT NULL,
	 "type" integer,
	PRIMARY KEY("id")
);

CREATE TABLE "user" (
	 "user_id" text NOT NULL,
	 "user_name" TEXT NOT NULL,
	 "user_email" TEXT,
	 "user_phone" TEXT,
	 "user_pwd" TEXT,
	 "create_time" TEXT NOT NULL,
	 "update_time" TEXT NOT NULL,
	 "user_auth" integer,
	 "status" integer NOT NULL default 1,
	 PRIMARY KEY("user_id")
);

