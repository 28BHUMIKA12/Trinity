create table mus_user(

	id int(11) auto_increment,
	email varchar(200) unique,
	password varchar(100),
	first_name varchar(200),
	last_name varchar(200),
	user_type varchar(200),
	points int(1),
	mobile varchar(40),
	gender varchar(20),
	height varchar(10),
	height_feet int(2),
	height_inches int(2),
	width varchar(10),
	age varchar(10),
	address text,
	zip varchar(20),
	city varchar(200),
	state varchar(200),
	country varchar(200),
	water_intake varchar(10),
	training_hrs_per_week varchar(10),
	goal text,
	body_type varchar(200),
	fitness_tip_by_trainer text,
	
	PRIMARY key(id)

)engine=innodb;

create table mus_user_weight(

	id int(11) auto_increment,
	user_id int(11),
	weight varchar(10),
	weight_lbs varchar(10),
	week int(2),
	dated varchar(20),
	
	PRIMARY key(id),
	FOREIGN key(user_id) REFERENCES mus_user(id) on DELETE CASCADE on UPDATE CASCADE	

)engine=innodb;


create table mus_exercise(
	
	id int(11) auto_increment,
	pic text,
	video text,
	title text,
	description text,
	dated varchar(100),
	PRIMARY key(id)
	
)engine=innodb;

create table mus_exercise_default(
	
	id int(11) auto_increment, 
	exercise_id int(11),
	weekday int(1),
	body_type varchar(200),
	gender varchar(20),
	goal varchar(200),
	dated varchar(100),

	PRIMARY key(id),
	FOREIGN key(exercise_id) REFERENCES mus_exercise(id) on DELETE CASCADE on UPDATE CASCADE
	
)engine=innodb;

create table mus_user_exercise(
	
	id int(11) auto_increment, 
	user_id int(11),
	pic text,
	video text,
	title text,
	description text,
	dated varchar(100),

	PRIMARY key(id),
	FOREIGN key(user_id) REFERENCES mus_user(id) on DELETE CASCADE on UPDATE CASCADE
	
)engine=innodb;

create table mus_goal(

	id int(11) auto_increment,
	name varchar(200),
	
	PRIMARY KEY(id)

)engine=innodb;

create table mus_body_type(

	id int(11) auto_increment,
	name varchar(200),
	
	PRIMARY KEY(id)

)engine=innodb;

create table mus_meal(
	
	id int(11) auto_increment,
	name varchar(200),
	
	PRIMARY KEY(id)

)engine=innodb;


create table mus_user_required(

	id int(11) auto_increment,
	user_id int(11),
	meal_id int(11),
	cal varchar(100),
	protein varchar(100),
	fat varchar(100),
	carbs varchar(100),
	fiber varchar(100),
	
	PRIMARY key(id),
	FOREIGN key(user_id) REFERENCES mus_user(id) on DELETE CASCADE on UPDATE CASCADE,
	FOREIGN key(meal_id) REFERENCES mus_meal(id) on DELETE CASCADE on UPDATE CASCADE

)engine=innodb;

create table mus_item(

	id int(11) auto_increment,
	name varchar(200),
	parent_id int(11),
	eat_type varchar(100),
	
	PRIMARY KEY(id),
	FOREIGN key(parent_id) REFERENCES mus_item(id) on DELETE CASCADE on UPDATE CASCADE

)engine=innodb;

create table mus_item_exclusive(

	id int(11) auto_increment,
	item_id int(11),
	x_id int(11),
	
	PRIMARY KEY(id),
	FOREIGN key(item_id) REFERENCES mus_item(id) on DELETE CASCADE on UPDATE CASCADE,
	FOREIGN key(x_id) REFERENCES mus_item(id) on DELETE CASCADE on UPDATE CASCADE

)engine=innodb;


create table mus_meal_item(

	id int(11) auto_increment,
	item_id int(11),
	meal_id int(11),	
	PRIMARY KEY(id),
	FOREIGN key(item_id) REFERENCES mus_item(id) on DELETE CASCADE on UPDATE CASCADE,
	FOREIGN key(meal_id) REFERENCES mus_meal(id) on DELETE CASCADE on UPDATE CASCADE

)engine=innodb;

create table mus_item_stats(

	id int(11) auto_increment,
	item_id int(11),
	weight varchar(100),
	weight_unit varchar(100),
	grams varchar(100),
	calories varchar(100),
	protein varchar(100),
	fat varchar(100),
	carbs varchar(100),
	fiber varchar(100),
	is_custom varchar(100),
	
	PRIMARY KEY(id),
	FOREIGN key(item_id) REFERENCES mus_item(id) on DELETE CASCADE on UPDATE CASCADE

)engine=innodb;


create table mus_user_meal(
	
	id int(11) auto_increment,
	name varchar(200),
	code varchar(200),
	user_id int(11),
	eat_type varchar(100),
	
	PRIMARY KEY(id),
	FOREIGN key(user_id) REFERENCES mus_user(id) on DELETE CASCADE on UPDATE CASCADE
	
)engine=innodb;

create table mus_user_meal_items(
	
	id int(11) auto_increment,
	user_meal_id int(11),
	meal_id int(11),
	item_id int(11),
	item_stats_id int(11),
	eat_type varchar(200),
	
	PRIMARY KEY(id),
	FOREIGN key(user_meal_id) REFERENCES mus_user_meal(id) on DELETE CASCADE on UPDATE CASCADE,
	FOREIGN key(item_id) REFERENCES mus_item(id) on DELETE CASCADE on UPDATE CASCADE,
	FOREIGN key(meal_id) REFERENCES mus_meal(id) on DELETE CASCADE on UPDATE CASCADE,
	FOREIGN key(item_stats_id) REFERENCES mus_item_stats(id) on DELETE CASCADE on UPDATE CASCADE

)engine=innodb;

create table mus_trainer_trainee(

	id int(11) auto_increment,
	trainer_id int(11),
	trainee_id int(11),
	dated varchar(20),
	
	PRIMARY KEY(id),
	FOREIGN key(trainer_id) REFERENCES mus_user(id) on DELETE CASCADE on UPDATE CASCADE,
	FOREIGN key(trainee_id) REFERENCES mus_user(id) on DELETE CASCADE on UPDATE CASCADE

)engine=innodb;

create table mus_question(

	id int(11) auto_increment,
	title text,
	data_type varchar(200),
	description text,
	image text,
	video text,
	is_active boolean,
	
	PRIMARY key(id)

)engine=innodb;

create table mus_answer(

	id int(11) auto_increment,
	question_id int(11),
	data_type varchar(200),
	data text,
	is_correct boolean,
	
	PRIMARY key(id),
	FOREIGN key(question_id) REFERENCES mus_question(id) on DELETE CASCADE on UPDATE CASCADE

)engine=innodb;

create table mus_quiz(

	id int(11) auto_increment,
	user_id int(100),
	quiz_start varchar(100),
	quiz_end varchar(100),
	is_stopped boolean,
	stopped_time varchar(100),
	total_time_played text,
	
	PRIMARY KEY(id),
	FOREIGN key(user_id) REFERENCES mus_user(id) on DELETE CASCADE on UPDATE CASCADE
	
)engine=innodb;

create table mus_quiz_played(

	id int(11) auto_increment,
	user_id int(11),
	question_id int(11),
	answer_given int(2),
	is_correct boolean,
	dated varchar(200),
	
	PRIMARY KEY(id),
	FOREIGN key(user_id) REFERENCES mus_user(id) on DELETE CASCADE on UPDATE CASCADE,	
	FOREIGN key(question_id) REFERENCES mus_question(id) on DELETE CASCADE on UPDATE CASCADE	
	
)engine = innodb;





