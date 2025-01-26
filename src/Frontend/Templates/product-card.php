<?php
/**
 * @var string $image
 * @var string $title
 * @var string $price
 * @var string $description
 * @var string $book_url
 */

?>
<div class="travel-product-card">
    <?php if ($image): ?>
        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" class="product-image"/>
    <?php endif; ?>
    <h3 class="product-title"><?php echo esc_html($title); ?></h3>
    <div class="product-price"><?php echo esc_html($price); ?></div>
    <div class="product-description"><?php echo wp_kses_post($description); ?></div>
    <?php if ($book_url): ?>
         <a href="<?php echo esc_url($book_url); ?>" class="product-book-button" target="_blank"><?php esc_html_e('Book Now', 'travel-booking-plugin'); ?></a>
    <?php endif; ?>

</div>