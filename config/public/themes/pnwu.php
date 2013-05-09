<?

/* REPLACE THE STATS BAR */

function draw_the_leaderboard($campaign = NULL) {
  draw_promo_c2('pnwu');
}  
add_action('after_campaign_appeal_message', 'draw_the_leaderboard');
