-- noinspection SqlResolveForFile

CREATE TABLE page_versions (

    page_version_id     INT PRIMARY KEY AUTO_INCREMENT,

    page_id             INT NOT NULL,

    user_id             INT NOT NULL,

    saved_at            INT NOT NULL,

    markup_type         VARCHAR(32) NOT NULL,

    title               VARCHAR(191) NOT NULL,

    body                TEXT,

    raw_body            TEXT,

    excerpt             TEXT,

    FOREIGN KEY page_versions_page_id_fkey (page_id)
        REFERENCES pages (page_id),

    FOREIGN KEY page_versions_user_id_fkey (user_id)
      REFERENCES users (user_id)

) ENGINE=InnoDB CHARACTER SET utf8mb4;


ALTER TABLE pages ADD CONSTRAINT pages_latest_version_id_fkey
    FOREIGN KEY (page_id, latest_version_id)
    REFERENCES page_versions (page_id, page_version_id);


ALTER TABLE pages ADD CONSTRAINT pages_published_version_id_fkey
    FOREIGN KEY (page_id, published_version_id)
    REFERENCES page_versions (page_id, page_version_id);
