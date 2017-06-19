<?php
echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<rss version="2.0"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
     xmlns:admin="http://webns.net/mvcb/"
     xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"    
     >
    <channel>
        <title><?php echo $feed_name; ?></title>
        <link><?php echo $feed_url; ?></link>    
        <description><?php echo $page_description; ?></description>
        <dc:language><?php echo $page_language; ?></dc:language>
        <dc:creator><?php echo $creator_email; ?></dc:creator>
        <dc:rights>Copyright <?php echo gmdate("Y", time()); ?></dc:rights>
        <admin:generatorAgent rdf:resource="http://www.codeigniter.com/" />

        <?php
        if (is_array($post_details) && count($post_details)) {
            foreach ($post_details as $post) {
                ?>   
                <item>
                    <title><?php echo xml_convert($post['post_title']); ?></title>
                    <link><?php echo $urlen . '/' . $post['post_slug']; ?></link>
                    <guid><?php echo $urlen . '/' . $post['post_slug']; ?></guid>
                    <description>
                        <![CDATA[
                        <img width="100" height="100" src="<?php echo $imagUrl . $post['feature_image']; ?>" class="attachment-thumbnail wp-post-image" alt="<?php echo $post['post_title']; ?>" title="<?php echo $post['post_title']; ?>" /><br/>
                        ]]>
                        <?php echo xml_convert($post['seo_description']); ?>
                    </description>

                </item>        
                <?php
            }
        }
        ?>    

    </channel>
</rss>