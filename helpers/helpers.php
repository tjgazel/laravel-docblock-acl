<?php

if (!function_exists('aclPrefixURL')) {
    function aclPrefixURL(){
        return \TJGazel\LaravelDocBlockAcl\Facades\Acl::getPrefixURL();
    }
}

if (!function_exists('aclPrefixRoutName')) {
    function aclPrefixRoutName(){
        return \TJGazel\LaravelDocBlockAcl\Facades\Acl::getPrefixRouteName();
    }
}