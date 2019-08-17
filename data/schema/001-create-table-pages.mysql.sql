-- noinspection SqlResolveForFile

CREATE TABLE pages (

    page_id                 INT PRIMARY KEY AUTO_INCREMENT,

    page_type               VARCHAR(32) NOT NULL,

    created_at              INT NOT NULL,

    updated_at              INT NOT NULL,

    published_at            INT,

    expires_at              INT,

    deleted_at              INT,

    latest_revision_id      INT,

    published_revision_id   INT,

    is_pinned               TINYINT(1) NOT NULL DEFAULT 0,

    priority                INT NOT NULL DEFAULT 0,

    slug                    VARCHAR(191) NOT NULL UNIQUE,

    INDEX pages_page_type_deleted_at_idx (page_type, deleted_at)

) ENGINE=InnoDB CHARACTER SET utf8mb4;
