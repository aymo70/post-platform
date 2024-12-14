class Post {
  final int id;
  final String content;
  final String? imageUrl;
  final int commentsCount;
  final int likesCount;

  Post({
    required this.id,
    required this.content,
    this.imageUrl,
    required this.commentsCount,
    required this.likesCount,
  });

  // Factory method لتحويل JSON إلى كائن Post
  factory Post.fromJson(Map<String, dynamic> json) {
    return Post(
      id: json['id'],
      content: json['content'],
      imageUrl: json['image'] != null ? json['image'] as String : null,
      commentsCount: json['comments_count'] ?? 0,
      likesCount: json['likes_count'] ?? 0,
    );
  }

  // لتحويل الكائن إلى JSON (لإرساله إذا لزم الأمر)
  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'content': content,
      'image': imageUrl,
      'comments_count': commentsCount,
      'likes_count': likesCount,
    };
  }
}
