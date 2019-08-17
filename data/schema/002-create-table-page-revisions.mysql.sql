-- noinspection SqlResolveForFile

CREATE TABLE page_revisions (

    page_revision_id    INT PRIMARY KEY AUTO_INCREMENT,

    page_id             INT NOT NULL,

    user_id             INT NOT NULL,

    saved_at            INT NOT NULL,

    markup_type         VARCHAR(32) NOT NULL,

    title               VARCHAR(191) NOT NULL,

    body                TEXT,

    excerpt             TEXT,

    FOREIGN KEY page_revisions_page_id_fkey (page_id)
        REFERENCES pages (page_id),

    FOREIGN KEY page_revisions_user_id_fkey (user_id)
      REFERENCES users (user_id)

) ENGINE=InnoDB CHARACTER SET utf8mb4;


ALTER TABLE pages ADD CONSTRAINT pages_latest_revision_id_fkey
    FOREIGN KEY (page_id, latest_revision_id)
    REFERENCES page_revisions (page_id, page_revision_id);


ALTER TABLE pages ADD CONSTRAINT pages_published_revision_id_fkey
    FOREIGN KEY (page_id, published_revision_id)
    REFERENCES page_revisions (page_id, page_revision_id);
