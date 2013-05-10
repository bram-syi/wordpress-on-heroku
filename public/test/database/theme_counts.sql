SELECT theme,
    count(*) AS use_count,
    group_concat(DISTINCT wp_blogs.domain) AS used_on
FROM campaigns
INNER JOIN wp_1_posts ON campaigns.post_id = wp_1_posts.id
LEFT JOIN wp_1_postmeta ON wp_1_posts.id = wp_1_postmeta.post_id
LEFT JOIN wp_blogs ON substring_index(wp_blogs.domain, '.', 1) = wp_1_postmeta.meta_value
WHERE wp_1_postmeta.meta_key = 'syi_tag'
    AND campaigns.theme != ''
GROUP BY campaigns.theme
ORDER BY use_count DESC 