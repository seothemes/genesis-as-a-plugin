<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Genesis\Comments
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://my.studiopress.com/themes/genesis/
 */

add_action( 'genesis_after_entry', 'genesis_get_comments_template' );
/**
 * Output the comments at the end of entries.
 *
 * Load comments only if we are on a post, page, or CPT that supports comments, and only if comments or trackbacks are enabled.
 *
 * @since 1.1.0
 *
 * @return void Return early if post type does not support `comments`.
 */
function genesis_get_comments_template() {

	if ( ! post_type_supports( get_post_type(), 'comments' ) ) {
		return;
	}

	if ( is_singular() && ! in_array( get_post_type(), [ 'post', 'page' ], true ) ) {
		comments_template( '', true );
	} elseif ( is_singular( 'post' ) && ( genesis_get_option( 'trackbacks_posts' ) || genesis_get_option( 'comments_posts' ) ) ) {
		comments_template( '', true );
	} elseif ( is_singular( 'page' ) && ( genesis_get_option( 'trackbacks_pages' ) || genesis_get_option( 'comments_pages' ) ) ) {
		comments_template( '', true );
	}

}

add_action( 'genesis_comments', 'genesis_do_comments' );
/**
 * Echo Genesis default comment structure.
 *
 * Does the `genesis_list_comments` action.
 *
 * Applies the `genesis_title_comments`, `genesis_prev_comments_link_text`, `genesis_next_comments_link_text`,
 * `genesis_no_comments_text` and `genesis_comments_closed_text` filters.
 *
 * @since 1.1.2
 *
 * @global WP_Query $wp_query Query object.
 *
 * @return void Return early if on a page with Genesis page comments off, or on a post with Genesis post comments off.
 */
function genesis_do_comments() {

	global $wp_query;

	// Bail if comments are off for this post type.
	if ( ( is_page() && ! genesis_get_option( 'comments_pages' ) ) || ( is_single() && ! genesis_get_option( 'comments_posts' ) ) ) {
		return;
	}

	$no_comments_text     = apply_filters( 'genesis_no_comments_text', '' );
	$comments_closed_text = apply_filters( 'genesis_comments_closed_text', '' );

	if ( ! empty( $wp_query->comments_by_type['comment'] ) && have_comments() ) {

		genesis_markup(
			[
				'open'    => '<div %s>',
				'context' => 'entry-comments',
			]
		);

		$comments_title = sprintf( '<h3>%s</h3>', esc_html__( 'Comments', 'genesis' ) );

		/**
		 * Comments title filter
		 *
		 * Allows the comments title to be filtered.
		 *
		 * @since ???
		 *
		 * @param string $comments_title The comments title.
		 */
		$comments_title = apply_filters( 'genesis_title_comments', $comments_title );

		echo $comments_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitize done prior to filter application
		printf( '<ol %s>', genesis_attr( 'comment-list' ) );

			/**
			 * Fires inside comments list markup.
			 *
			 * @since 1.0.0
			 */
			do_action( 'genesis_list_comments' );

		echo '</ol>';

		// Comment Navigation.
		$prev_link = get_previous_comments_link( apply_filters( 'genesis_prev_comments_link_text', '' ) );
		$next_link = get_next_comments_link( apply_filters( 'genesis_next_comments_link_text', '' ) );

		if ( $prev_link || $next_link ) {

			$pagination  = sprintf( '<div class="pagination-previous alignleft">%s</div>', $prev_link );
			$pagination .= sprintf( '<div class="pagination-next alignright">%s</div>', $next_link );

			genesis_markup(
				[
					'open'    => '<div %s>',
					'close'   => '</div>',
					'content' => $pagination,
					'context' => 'comments-pagination',
				]
			);

		}

		genesis_markup(
			[
				'close'   => '</div>',
				'context' => 'entry-comments',
			]
		);

	} elseif ( $no_comments_text && 'open' === get_post()->comment_status ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Text Produced by a third party
		echo sprintf( '<div %s>', genesis_attr( 'entry-comments' ) ) . $no_comments_text . '</div>';
	} elseif ( $comments_closed_text ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Text Produced by a third party
		echo sprintf( '<div %s>', genesis_attr( 'entry-comments' ) ) . $comments_closed_text . '</div>';
	}

}

add_action( 'genesis_pings', 'genesis_do_pings' );
/**
 * Echo Genesis default trackback structure.
 *
 * Does the `genesis_list_args` action.
 *
 * Applies the `genesis_no_pings_text` filter.
 *
 * @since 1.1.2
 *
 * @global WP_Query $wp_query Query object.
 *
 * @return void Return early if on a page with Genesis page trackbacks off, or on a
 *              post with Genesis post trackbacks off.
 */
function genesis_do_pings() {

	global $wp_query;

	// Bail if trackbacks are off for this post type.
	if ( ( is_page() && ! genesis_get_option( 'trackbacks_pages' ) ) || ( is_single() && ! genesis_get_option( 'trackbacks_posts' ) ) ) {
		return;
	}

	// If have pings.
	if ( ! empty( $wp_query->comments_by_type['pings'] ) && have_comments() ) {

		if ( empty( $wp_query->comments_by_type['comment'] ) && ! has_filter( 'genesis_no_comments_text' ) ) {
			add_filter( 'genesis_attr_entry-pings', 'genesis_attributes_entry_comments' );
		}

		genesis_markup(
			[
				'open'    => '<div %s>',
				'context' => 'entry-pings',
			]
		);

		$pings_title = sprintf( '<h3>%s</h3>', esc_html__( 'Trackbacks', 'genesis' ) );

		/**
		 * Pings/trackbacks title filter
		 *
		 * Allows the pings/trackbacks title to be filtered.
		 *
		 * @since ???
		 *
		 * @param string $pings_title The pings/trackbacks title.
		 */
		$pings_title = apply_filters( 'genesis_title_pings', $pings_title );

		echo $pings_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitize done prior to filter application
		echo '<ol class="ping-list">';

			/**
			 * Fires inside the pings list markup.
			 *
			 * @since 1.0.0
			 */
			do_action( 'genesis_list_pings' );

		echo '</ol>';

		genesis_markup(
			[
				'close'   => '</div>',
				'context' => 'entry-pings',
			]
		);

	} else {

		/**
		 * No pings text filter
		 *
		 * Allows the text that displays when no pings are present to be filtered.
		 *
		 * @since ???
		 *
		 * @param string No pings text.
		 */
		$no_pings_text = apply_filters( 'genesis_no_pings_text', '' );

		echo $no_pings_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- no sanitization necessary here

	}

}

add_action( 'genesis_list_comments', 'genesis_default_list_comments' );
/**
 * Output the list of comments.
 *
 * Applies the `genesis_comment_list_args` filter.
 *
 * @since 1.0.0
 *
 * @see genesis_html5_comment_callback() HTML5 callback.
 * @see genesis_comment_callback()       XHTML callback.
 */
function genesis_default_list_comments() {

	$defaults = [
		'type'        => 'comment',
		'avatar_size' => 48,
		'format'      => 'html5', // Not necessary, but a good example.
		'callback'    => 'genesis_html5_comment_callback',
	];

	$args = apply_filters( 'genesis_comment_list_args', $defaults );

	wp_list_comments( $args );

}

add_action( 'genesis_list_pings', 'genesis_default_list_pings' );
/**
 * Output the list of trackbacks.
 *
 * Applies the `genesis_ping_list_args` filter.
 *
 * @since 1.0.0
 */
function genesis_default_list_pings() {

	$args = apply_filters(
		'genesis_ping_list_args',
		[
			'type' => 'pings',
		]
	);

	wp_list_comments( $args );

}

/**
 * Comment callback for {@link genesis_default_list_comments()} if HTML5 is active.
 *
 * Does `genesis_before_comment` and `genesis_after_comment` actions.
 *
 * Applies `comment_author_says_text` and `genesis_comment_awaiting_moderation` filters.
 *
 * @since 2.0.0
 *
 * @param stdClass $comment Comment object.
 * @param array    $args    Comment args.
 * @param int      $depth   Depth of current comment.
 */
function genesis_html5_comment_callback( $comment, array $args, $depth ) {
	?>

	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
	<article <?php echo genesis_attr( 'comment' ); ?>>

		<?php
		/**
		 * Fires inside single comment callback, before comment markup.
		 *
		 * @since 1.1.0
		 */
		do_action( 'genesis_before_comment' );
		?>

		<header <?php echo genesis_attr( 'comment-header' ); ?>>
			<p <?php echo genesis_attr( 'comment-author' ); ?>>
				<?php
				if ( 0 !== $args['avatar_size'] ) {
					echo get_avatar( $comment, $args['avatar_size'] );
				}
				$author = get_comment_author();
				$url    = get_comment_author_url();

				if ( ! empty( $url ) && 'http://' !== $url ) {
					$author = sprintf( '<a href="%s" %s>%s</a>', esc_url( $url ), genesis_attr( 'comment-author-link' ), $author );
				}

				/**
				 * Filter the "comment author says" text.
				 *
				 * Allows developer to filter the "comment author says" text so it can say something different, or nothing at all.
				 *
				 * @since unknown
				 *
				 * @param string $text Comment author says text.
				 */
				$comment_author_says_text = apply_filters( 'comment_author_says_text', __( 'says', 'genesis' ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

				if ( ! empty( $comment_author_says_text ) ) {
					$comment_author_says_text = ' <span class="says">' . $comment_author_says_text . '</span>';
				}

				genesis_markup(
					[
						'open'    => '<span %s>',
						'close'   => '</span>',
						'content' => $author,
						'context' => 'comment-author-name',
					]
				);

				$comment_author_says_allowed = [
					'span' => [
						'class' => [],
					],
				];

				echo wp_kses( $comment_author_says_text, $comment_author_says_allowed );
				?>
			</p>

			<?php
			/**
			 * Allows developer to control whether to print the comment date.
			 *
			 * @since 2.2.0
			 *
			 * @param bool   $comment_date Whether to print the comment date.
			 * @param string $post_type    The current post type.
			 */
			$comment_date = apply_filters( 'genesis_show_comment_date', true, get_post_type() );

			if ( $comment_date ) {
				$comment_time_link = genesis_markup(
					[
						'open'    => '<a %s>',
						'context' => 'comment-time-link',
						'content' => esc_html(
							sprintf(
								/* translators: 1: A date, 2: A time. */
								__( '%1$s at %2$s', 'genesis' ),
								get_comment_date(),
								get_comment_time()
							)
						),
						'close'   => '</a>',
						'params'  => [
							'comment' => $comment,
						],
						'echo'    => false,
					]
				);
				$comment_time      = genesis_markup(
					[
						'open'    => '<time %s>',
						'context' => 'comment-time',
						'content' => $comment_time_link,
						'close'   => '</time>',
						'echo'    => false,
					]
				);
				genesis_markup(
					[
						'open'    => '<p %s>',
						'context' => 'comment-meta',
						'content' => $comment_time,
						'close'   => '</p>',
					]
				);
			}

			edit_comment_link( __( '(Edit)', 'genesis' ), ' ' );
			?>
		</header>

		<div <?php echo genesis_attr( 'comment-content' ); ?>>
			<?php if ( ! $comment->comment_approved ) : ?>
				<?php
				/**
				 * Filter the "comment awaiting moderation" text.
				 *
				 * Allows developer to filter the "comment awaiting moderation" text so it can say something different, or nothing at all.
				 *
				 * @since unknown
				 *
				 * @param string $text Comment awaiting moderation text.
				 */
				$comment_awaiting_moderation_text = apply_filters( 'genesis_comment_awaiting_moderation', __( 'Your comment is awaiting moderation.', 'genesis' ) );
				?>
				<p class="alert">
				<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Text Produced by a third party ?>
				<?php echo $comment_awaiting_moderation_text; ?></p>
			<?php endif; ?>

			<?php comment_text(); ?>
		</div>

		<?php
		comment_reply_link(
			array_merge(
				$args,
				[
					'depth'  => $depth,
					'before' => sprintf( '<div %s>', genesis_attr( 'comment-reply' ) ),
					'after'  => '</div>',
				]
			)
		);
		?>

		<?php
		/**
		 * Fires inside legacy single comment callback, after comment markup.
		 *
		 * @since 1.1.0
		 */
		do_action( 'genesis_after_comment' );
		?>

	</article>
	<?php
	// No ending </li> tag because of comment threading.
}

add_action( 'genesis_comment_form', 'genesis_do_comment_form' );
/**
 * Optionally show the comment form.
 *
 * Genesis asks WP for the HTML5 version of the comment form - it uses {@link genesis_comment_form_args()} to revert to
 * XHTML form fields when child theme does not support HTML5.
 *
 * @since 1.0.0
 *
 * @return void Return early if comments are closed via Genesis for this page or post.
 */
function genesis_do_comment_form() {

	// Bail if comments are closed for this post type.
	if ( ( is_page() && ! genesis_get_option( 'comments_pages' ) ) || ( is_single() && ! genesis_get_option( 'comments_posts' ) ) ) {
		return;
	}

	comment_form(
		[
			'format' => 'html5',
		]
	);

}

add_filter( 'get_comments_link', 'genesis_comments_link_filter', 10, 2 );
/**
 * Filter the comments link. If post has comments, link to #comments div. If no, link to #respond div.
 *
 * @since 2.0.1
 *
 * @param string      $link    Post comments permalink with '#comments' appended.
 * @param int|WP_Post $post_id Post ID or WP_Post object.
 * @return string URL to comments if they exist, otherwise URL to the comment form.
 */
function genesis_comments_link_filter( $link, $post_id ) {

	if ( 0 === get_comments_number() ) {
		return get_permalink( $post_id ) . '#respond';
	}

	return $link;

}
