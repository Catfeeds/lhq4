<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => C('alipay_app_id'),

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIIEpQIBAAKCAQEAzwIEAbFRxIZ3Pi8XJlmqVJyMTBtUKZprIh4ZoWrxrlMFO8rLMM2edMvjg6SHzgGUxm68QR1UwTzu/M/VqKrHRyB74zjSTSqKxIA9ZTWDp0h73lz1MoRRhz+Bxs2oSIYkowuWuHEDHWXyVMEdbJYvc9hyb2js8lg7M7v5dex6ILA7c0gM21vlNiH9P+VLAq8eipgNu4YvKiRMcLedV4Mwe4pNLd1wjy9DmikuxB+rToCqh+94hU7N0GbQ7A45ppLjTSeqSbVsPVPYHDE7TiWaRFPSIeEKobqW3mUjT8fiPMyOvvS4I+9/nTI9jIE2V35p9WoqgvXzaFbGAKuEHmHHqQIDAQABAoIBAQDBXzym6CFd185kBFE3veLjDynvnkfMZTdWs491vhTtpxfodtPATxOKPzvUqUlDIy76/k5U9TVLHcSR3QPVf1KQGsQfyeCJvQfL749qj8bf6VHssiFKg1g1YxmzwEFHHifzNswgl1E1kRoCGjOGT/Ub3kFn2TgeWlVTSnEJu1GKXeZb0iECzw/jwZ1GPqFxv8lcI8ZZU5RNl94+K4vMf7d6zRi2mMOJeO3kaT8FA4hZG+/sJsAbIHfF3HFXVQEYhYzIgr+YS4+i5u1A+xwnvroPm3NnFLWQV3gSj6XUZQM7cBz9VZR/9TSbIEEzOIJIm2iX5bNHT+qw/TE0Mbic3tORAoGBAPoONWPTVYrA5RfNMYoydbTd4eA8yXbHDCS7Nm8liKdC4P+GSsKRccj0R7/2X3dF+Hn15tqIgCmdHJi4DYm8vSJTZggvFJwfgIxIGJ5sJUwsbsYMbq4R3FkAS/ceS0nBSf0o3BZ+M0S4EtzfUQx/Euq6i0EgNA3Ax95+B9ffw0UjAoGBANPt08PSyKvWbfwDRSzZQF/wqrnxgbxQG+cNZSwFRUU3o3wJ4rDKuOHHQPGhIWD92/gAN5FIwpYOgd9E2m6F3bGHQ9lbGIq9vL+GyNKHmUubqQUMS/z3Her+5nkI8kS/VsbatuyGmZqNhaAwgE+TanAt0H8vQnuFTX6Lm/mpOErDAoGBAN14SidBHUmIcR+0mIT7Di+EObN+gz0VLJc3zQ2CI9dor5kBmyNa6HmAii/mCUW9jkaO2cmCDmlNAu/sS+lLZgSJ8xuaf1mLuTIGBPADfsFJtU7p4fmATjXxwlZ72VjYvixfzRaK7eOWWPaRP9qejeWiFQY4fIIV1bAk1dOIFw4bAoGAJV2iX71zWjDVdYCSGsU3P3Msdtpo+G3bbZKECw3m17xmm8EWcftcO5qpDbFONE6uEPzgz0HX5SMrrNTz+lap7todkV4pZpTriY1XLR9xXM5WL6iqYTCk2sEUm7SDGrHtt+PqKqvUu1ZGgCbtCJrCQoMhZrtLaoHnVu+AkbVPUocCgYEA1o5+uIS1WjUox9Ymw6yqZcq+3aXeQeEW1yKwhAHOef7FyS7y5fv9fmm5LAzBAWhZ0IWrw97MwuuMh9BMoIOmcmvwv+93c4VvOuHGeOarNKt7sPYtTaVy/OQWpkGVnzJJFr1VI3JTmY0zKsV0oleKJW/13mgv9mcXdB56prDvULs=",
		
		//异步通知地址
       'notify_url' => C('site_url')."/index.php?m=Weixin&c=AlipaySDK&a=PayNotifyCallBack",
		//同步跳转
		'return_url' =>C('site_url')."/index.php?m=Weixin&c=User&a=alipay_success",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAw2ub53gb3eIhIZg53ldBhlCmvMxGYhkN8ps+bstdqLTdkTYKZSwZQyLt8SyIiElAbssm1/CZLe8J3e/03cMSITBWrf2KzS66sTeJzeJ4kXujMafzYjyLzUdYj5Mj4ZudztJ2ClFniM8eD4QFl4ywygpF37bYMk9DS9tEkmbuKJ/urw2jhDcxXP7b7tW1X1/X06z14IHrDo2gINzvcli/AWrlcDbK+6NLGmZXDbN4q8xiRivIGWNIAUOFJQ9J2+IMH6RyHfzr11zl+GqdXV6M8KHSGbHLv9DX80eOJ07Ibe1cQQrYKvSC3Wfqldk3jK2R/GoKV6X4K//h8lrU/UwB4QIDAQAB",
		
	
);