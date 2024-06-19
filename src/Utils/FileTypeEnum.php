<?php

namespace Redux\Modular\Utils;

enum FileTypeEnum : string {
    case Controller = 'controllers';
    case Model = 'models';
    case Request = 'requests';
    case Resource = 'resources';
    case Service = 'services';
    case Rule = 'rules';
}
