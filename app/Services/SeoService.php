<?php

namespace App\Services;

class SeoService
{
    /**
     * Prepare SEO data when creating a new record.
     *
     * If OG/Twitter fields are empty, use the corresponding Meta fields.
     */
    public function prepareCreate(array $data): array
    {
        // OG
        if (empty($data['og_title']) && !empty($data['meta_title'])) {
            $data['og_title'] = $data['meta_title'];
        }

        if (empty($data['og_description']) && !empty($data['meta_description'])) {
            $data['og_description'] = $data['meta_description'];
        }

        // Twitter
        if (empty($data['twitter_title']) && !empty($data['meta_title'])) {
            $data['twitter_title'] = $data['meta_title'];
        }

        if (empty($data['twitter_description']) && !empty($data['meta_description'])) {
            $data['twitter_description'] = $data['meta_description'];
        }

        return $data;
    }

    /**
     * Prepare SEO data when updating an existing record.
     *
     * If OG/Twitter still contain their previous values,
     * they are considered unchanged and will follow the new Meta values.
     *
     * If the user manually changed OG/Twitter,
     * their new values are preserved.
     */
    public function prepareUpdate(
        ?string $oldMetaTitle,
        ?string $oldOgTitle,
        ?string $newMetaTitle,
        ?string $newOgTitle,

        ?string $oldMetaDescription,
        ?string $oldOgDescription,
        ?string $newMetaDescription,
        ?string $newOgDescription,

        ?string $oldTwitterTitle,
        ?string $oldTwitterDescription,
        ?string $newTwitterTitle,
        ?string $newTwitterDescription
    ): array {

        /*
         * OG TITLE
         *
         * If submitted OG title is the same as the old OG title,
         * user did not change it.
         *
         * Therefore make it follow the new Meta Title.
         */
        if ($newOgTitle === $oldOgTitle) {
            $newOgTitle = $newMetaTitle;
        }

        /*
         * OG DESCRIPTION
         */
        if ($newOgDescription === $oldOgDescription) {
            $newOgDescription = $newMetaDescription;
        }

        /*
         * TWITTER TITLE
         */
        if ($newTwitterTitle === $oldTwitterTitle) {
            $newTwitterTitle = $newMetaTitle;
        }

        /*
         * TWITTER DESCRIPTION
         */
        if ($newTwitterDescription === $oldTwitterDescription) {
            $newTwitterDescription = $newMetaDescription;
        }

        return [
            'og_title' => $newOgTitle,
            'og_description' => $newOgDescription,

            'twitter_title' => $newTwitterTitle,
            'twitter_description' => $newTwitterDescription,
        ];
    }
}
