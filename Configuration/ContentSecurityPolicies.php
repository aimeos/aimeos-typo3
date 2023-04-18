<?php

use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Directive;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Mutation;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\MutationCollection;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\MutationMode;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Scope;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\SourceKeyword;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\SourceScheme;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\UriValue;
use TYPO3\CMS\Core\Type\Map;


return Map::fromEntries([
    // Provide declarations for the backend
    Scope::backend(),

    new MutationCollection(
        // Results in `default-src 'self' 'data:', 'blob:' 'https://cdnjs.cloudflare.com' 'https://cdn.jsdelivr.net'`
        new Mutation(
            MutationMode::Extend,
            Directive::DefaultSrc,
            SourceKeyword::self,
            SourceScheme::data,
            SourceScheme::blob,
            new UriValue('https://cdnjs.cloudflare.com'),
            new UriValue('https://cdn.jsdelivr.net')
        ),

        // Results in `style-src 'self' 'unsafe-inline' 'https://cdnjs.cloudflare.com' 'https://cdn.jsdelivr.net'`
        new Mutation(
            MutationMode::Extend,
            Directive::StyleSrc,
            SourceKeyword::self,
            SourceKeyword::unsafeInline,
            new UriValue('https://cdnjs.cloudflare.com'),
            new UriValue('https://cdn.jsdelivr.net')
        ),

        // Results in `script-src 'self' 'unsafe-eval' 'https://cdnjs.cloudflare.com' 'https://cdn.jsdelivr.net'`
        new Mutation(
            MutationMode::Extend,
            Directive::ScriptSrc,
            SourceKeyword::self,
            SourceKeyword::unsafeEval,
            new UriValue('https://cdnjs.cloudflare.com'),
            new UriValue('https://cdn.jsdelivr.net'),
            new UriValue('https://cdn.jsdelivr.net')
        ),

        // Results in `connect-src 'self' https://*.deepl.com https://api.openai.com`
        new Mutation(
            MutationMode::Extend,
            Directive::ConnectSrc,
            SourceKeyword::self,
            new UriValue('https://*.deepl.com'),
            new UriValue('https://api.openai.com')
        ),

        // img-src \'self\' data: blob: https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://*.tile.openstreetmap.org https://aimeos.org
        new Mutation(
            MutationMode::Extend,
            Directive::ImgSrc,
            SourceKeyword::self,
            SourceScheme::data,
            SourceScheme::blob,
            new UriValue('https://cdnjs.cloudflare.com'),
            new UriValue('https://cdn.jsdelivr.net'),
            new UriValue('https://*.tile.openstreetmap.org'),
            new UriValue('https://aimeos.org')
        ),

        // Results in `frame-src https://www.youtube.com https://player.vimeo.com`
        new Mutation(
            MutationMode::Extend,
            Directive::FrameSrc,
            new UriValue('https://www.youtube.com'),
            new UriValue('https://player.vimeo.com')
        ),
    ),
]);