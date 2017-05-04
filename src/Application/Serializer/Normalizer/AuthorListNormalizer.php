<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Domain\Model\Author;
use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;

class AuthorListNormalizer implements DenormalizerInterface
{
    const KEY_NAME = 'name';
    const KEY_EMAIL = 'email';
    const KEY_ROLE = 'role';

    /**
     * @param Author[] $authorList
     *
     * @return array
     */
    public function normalize(array $authorList)
    {
        $normalizedList = [];
        foreach ($authorList as $author) {
            $normalizedAuthor = [self::KEY_NAME => $author->getName()];
            if ($author->getEmail()) {
                $normalizedAuthor[AuthorListNormalizer::KEY_EMAIL] = $author->getEmail();
            }
            if ($author->getRole()) {
                $normalizedAuthor[self::KEY_ROLE] = $author->getRole();
            }
            $normalizedList[] = $normalizedAuthor;
        }

        return $normalizedList;
    }

    /**
     * @param array $authorList
     *
     * @return Author[]
     */
    public function denormalize(array $authorList)
    {
        $normalizedList = [];
        foreach ($authorList as $authorData) {
            $normalizedList[] = new Author(
                $authorData[self::KEY_NAME],
                isset($authorData[self::KEY_EMAIL]) ? $authorData[self::KEY_EMAIL] : null,
                isset($authorData[self::KEY_ROLE]) ? $authorData[self::KEY_ROLE] : null
            );
        }

        return $normalizedList;
    }
}
