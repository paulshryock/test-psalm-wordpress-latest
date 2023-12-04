<?php

declare(strict_types=1);

namespace Pshryock\TestPsalmWordpressLatest;

use WP_Post;

$post = new WP_Post((object) ['post_type' => 'post']);

// Dynamic core action.
add_action(
  "save_post_{$post->post_type}",
  function(int $post_id, WP_Post $post, bool $update): void {
    print json_encode([
      'post_id' => $post_id,
      'post'    => $post,
      'update'  => $update,
    ]);
  },
  10,
  3,
);

// Dynamic core filter.
add_filter(
  "edit_{$post->post_type}_per_page",
  function(int $posts_per_page): int {
    return $posts_per_page + 1;
  },
  10,
  1,
);

// Third-party action with stub defined.
add_action(
  'elasticpress_loaded',
  function(): void {
    print 'elasticpress loaded';
  },
  10,
  0,
);

$slug = 'hello_world';

/**
 * Dynamic third-party action with no stub defined.
 *
 * This should throw a Psalm error.
 */
add_action(
  "ep_delete_{$slug}",
  function(int $object_id, string $slug): void {
    print json_encode(['object_id' => $object_id, 'slug' => $slug]);
  },
  10,
  2,
);

$verb = 'do';
$noun = 'thing';

/*
 * First-party dynamic hook with no stub defined.
 *
 * This should throw a Psalm error.
 */
add_filter(
  "{$verb}_some_{$noun}",
  function(string $value): string {
    return "hello {$value}!";
  },
  10,
  1,
);
