<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ': Özniteliğinin kabul edilmesi gerekir.',
    'active_url' => ': Özniteliği geçerli bir URL değil.',
    'after' => ': Özniteliği, tarihten sonraki bir tarih olmalıdır.',
    'after_or_equal' => ': Özniteliği, tarih tarihinden sonra veya ona eşit olmalıdır.',
    'alpha' => ': Özniteliği yalnızca harf içerebilir.',
    'alpha_dash' => ': Özniteliği yalnızca harf, rakam, kısa çizgi ve alt çizgi içerebilir.',
    'alpha_num' => ': Özniteliği yalnızca harf ve rakam içerebilir.',
    'array' => ': Özniteliği bir dizi olmalıdır.',
    'before' => ': Özniteliği, tarih tarihinden önce olmalıdır.',
    'before_or_equal' => ': Özniteliği, tarih öncesinde veya bu tarihe eşit olmalıdır.',
    'between' => [
        'numeric' => ': Özniteliği min ile: max arasında olmalıdır.',
        'file' => ': Özniteliği min ile: maks. Kilobayt arasında olmalıdır.',
        'string' => ': Özniteliği min ve: max karakterleri arasında olmalıdır.',
        'array' => ': Özniteliğinde min ve: max öğe bulunmalıdır.',
    ],
    'boolean' => ': Öznitelik alanı doğru veya yanlış olmalıdır.',
    'confirmed' => ': Öznitelik onayı eşleşmiyor.',
    'date' => ': Özniteliği geçerli bir tarih değil.',
    'date_equals' => ': Özniteliği,: tarihe eşit bir tarih olmalıdır.',
    'date_format' => ': Özniteliği format: format ile eşleşmiyor.',
    'different' => ': Özniteliği ve: diğer farklı olmalıdır.',
    'digits' => ': Özniteliği: rakamlar olmalıdır.',
    'digits_between' => ': Özniteliği min ve: maks rakamlar arasında olmalıdır.',
    'dimensions' => ': Özniteliğinde geçersiz resim boyutları var.',
    'distinct' => ': Öznitelik alanı yinelenen bir değere sahip.',
    'email' => ': Özniteliği, geçerli bir e-posta adresi olmalıdır.',
    'ends_with' => ': Özniteliği şunlardan biriyle bitmelidir:: değerler',
    'exists' => 'Seçili: öznitelik geçersiz.',
    'file' => ': Özniteliği bir dosya olmalıdır.',
    'filled' => ': Öznitelik alanı bir değere sahip olmalıdır.',
    'gt' => [
        'numeric' => ': Özniteliği: değerinden büyük olmalıdır.',
        'file' => ': Özniteliği, değer kilobaytından büyük olmalıdır.',
        'string' => ': Özniteliği,: değer karakterinden büyük olmalıdır.',
        'array' => ': Özniteliği, değer öğesinden daha fazla öğeye sahip olmalıdır.',
    ],
    'gte' => [
        'numeric' => ': Özniteliği: değerinden büyük veya eşit olmalıdır.',
        'file' => ': Özniteliği: değer kilobaytından büyük veya eşit olmalıdır.',
        'string' => ': Özniteliği: değer karakterlerinden büyük veya eşit olmalıdır.',
        'array' => ': Özniteliği,: değer öğelerine veya daha fazlasına sahip olmalıdır.',
    ],
    'image' => ': Özniteliği bir resim olmalıdır.',
    'in' => 'Seçili: özniteliği geçersiz.',
    'in_array' => ': attribute alanı değil var olmak içinde:diğer',
    'integer' => ': nitelik alanı değil var olmak içinde:diğer',
    'ip' => ': Özniteliği geçerli bir IP adresi olmalıdır.',
    'ipv4' => ': Özniteliği geçerli bir IPv4 adresi olmalıdır.',
    'ipv6' => ': Özniteliği geçerli bir IPv6 adresi olmalıdır.',
    'json' => ': Özniteliği geçerli bir JSON dizesi olmalıdır.',
    'lt' => [
        'numeric' => ': Özniteliği şu değerden küçük olmalıdır: değer.',
        'file' => ': Özniteliği, kilobayt değerinden küçük olmalıdır.',
        'string' => ': Özniteliği,: değer karakterinden küçük olmalıdır.',
        'array' => ': Özniteliğinin değer öğesinden daha az olması gerekir.',
    ],
    'lte' => [
        'numeric' => ': Özniteliği: değerinden küçük veya eşit olmalıdır.',
        'file' => ': Özniteliği: değer kilobaytından küçük veya eşit olmalıdır.',
        'string' => ': Özniteliği: değer karakterlerinden küçük veya eşit olmalıdır.',
        'array' => ': Özniteliğinde en fazla: değer öğesi bulunmamalıdır.',
    ],
    'max' => [
        'numeric' => ': Özniteliği şu değerden büyük olamaz: maks.',
        'file' => ': Özniteliği, en fazla kilobayttan büyük olamaz.',
        'string' => ': Özniteliği en fazla: en fazla karakter olabilir.',
        'array' => ': Özniteliğinde en fazla: en fazla öğe olabilir.',
    ],
    'mimes' => ': Özniteliği,:: değerler türünde bir dosya olmalıdır.',
    'mimetypes' => ': Özniteliği,:: değerler türünde bir dosya olmalıdır.',
    'min' => [
        'numeric' => ': Özniteliği en az: min.',
        'file' => ': Özniteliği en az: min kilobayt olmalıdır.',
        'string' => ': Özniteliği en az: min karakter olmalıdır.',
        'array' => ': Özniteliği en az: min karakter olmalıdır.',
    ],
    'not_in' => 'Seçildi: özniteliği geçersiz.',
    'not_regex' => ': Öznitelik biçimi geçersiz.',
    'numeric' => ': Özniteliği bir sayı olmalıdır.',
    'present' => ': Öznitelik alanı mevcut olmalıdır.',
    'regex' => ': Öznitelik biçimi geçersiz.',
    'required' => ': Öznitelik alanı gereklidir.',
    'required_if' => ': Öznitelik alanı şu durumlarda gereklidir: diğer: değer.',
    'required_unless' => ': Öznitelik alanı, şu koşulların dışında gereklidir: other, içinde: değerler.',
    'required_with' => ': Attribute alanı,: değerler mevcutsa gereklidir.',
    'required_with_all' => ': Öznitelik alanı şu durumlarda gereklidir: değerler mevcutsa.',
    'required_without' => ': Öznitelik alanı şu durumlarda gereklidir: değerler mevcut değil.',
    'required_without_all' => ': Özellik alanı,: değerlerinden hiçbiri olmadığında gereklidir.',
    'same' => ': Özniteliği ve: diğer eşleşmelidir.',
    'size' => [
        'numeric' => ': Özniteliği: boyut olmalıdır.',
        'file' => ': Özniteliği, boyut kilobayt olmalıdır.',
        'string' => ': Özniteliği: boyut karakterleri olmalıdır.',
        'array' => ': Özelliği,: size öğeleri içermelidir.',
    ],
    'starts_with' => ': Özniteliği şunlardan biriyle başlamalıdır:: değerler',
    'string' => ': Özniteliği bir dize olmalıdır.',
    'timezone' => ': Özniteliği geçerli bir bölge olmalıdır.',
    'unique' => ': Özniteliği zaten alınmış.',
    'uploaded' => ': Özniteliği yüklenemedi.',
    'url' => ': Öznitelik biçimi geçersiz.',
    'uuid' => ': Özniteliği geçerli bir UUID olmalıdır.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'özel mesaj',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
