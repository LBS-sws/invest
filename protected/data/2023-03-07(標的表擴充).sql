
-- ----------------------------
-- Table structure for inv_treaty
-- ----------------------------
ALTER TABLE inv_treaty ADD COLUMN capital_text float(15,2) NULL DEFAULT NULL COMMENT '注册资本' AFTER state_type;
ALTER TABLE inv_treaty ADD COLUMN holder_text text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '股东名称及股比' AFTER state_type;
ALTER TABLE inv_treaty ADD COLUMN trait_text text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '客户类别/特点' AFTER state_type;
ALTER TABLE inv_treaty ADD COLUMN appeal_text text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '对方诉求' AFTER state_type;
ALTER TABLE inv_treaty ADD COLUMN lbs_city int(1) NOT NULL DEFAULT 1 COMMENT '是否为LBS空白城市 1：是 0：否' AFTER state_type;

-- ----------------------------
-- Table structure for inv_treaty_info
-- ----------------------------
ALTER TABLE inv_treaty_info ADD COLUMN participant varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '参与者' AFTER remark;
