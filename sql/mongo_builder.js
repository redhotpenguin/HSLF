db.mobile_user.ensureIndex({device_identifier:1, tenant_id:1}, {unique:true});