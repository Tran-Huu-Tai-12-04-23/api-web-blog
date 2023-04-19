-- create DATABASE blog_web_midtern;

CREATE TABLE Users (
   UserID INT PRIMARY KEY AUTO_INCREMENT,
   Username VARCHAR(50) NOT NULL,
   Password VARCHAR(200) NOT NULL,
   Email VARCHAR(50),
   ProfileImage text default 'https://thumbs.dreamstime.com/b/default-avatar-profile-image-vector-social-media-user-icon-potrait-182347582.jpg',
   Bio TEXT,
   isAdmin boolean DEFAULT false
);

CREATE TABLE Posts (
   PostID INT PRIMARY KEY AUTO_INCREMENT,
   UserID INT NOT NULL,
   Title VARCHAR(100) NOT NULL,
   Content TEXT NOT NULL,
   ImageURL VARCHAR(100),
   VideoURL VARCHAR(100),
   CreatedAt DATETIME,
   FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

CREATE TABLE Comments (
   CommentID INT PRIMARY KEY AUTO_INCREMENT,
   UserID INT NOT NULL,
   PostID INT NOT NULL,
   CommentText TEXT NOT NULL,
   CreatedAt DATETIME,
   FOREIGN KEY (UserID) REFERENCES Users(UserID),
   FOREIGN KEY (PostID) REFERENCES Posts(PostID)
);

CREATE TABLE Likes (
   LikeID INT PRIMARY KEY AUTO_INCREMENT,
   UserID INT NOT NULL,
   PostID INT NOT NULL,
   CreatedAt DATETIME,
   FOREIGN KEY (UserID) REFERENCES Users(UserID),
   FOREIGN KEY (PostID) REFERENCES Posts(PostID)
);

CREATE TABLE Friendships (
   FriendshipID INT PRIMARY KEY AUTO_INCREMENT,
   User1ID INT NOT NULL,
   User2ID INT NOT NULL,
   Status VARCHAR(20) NOT NULL,
   CreatedAt DATETIME,
   FOREIGN KEY (User1ID) REFERENCES Users(UserID),
   FOREIGN KEY (User2ID) REFERENCES Users(UserID)
);
