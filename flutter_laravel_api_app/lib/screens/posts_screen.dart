// import 'package:flutter/material.dart';
// import '../services/api_service.dart';

// class PostsScreen extends StatefulWidget {
//   @override
//   _PostsScreenState createState() => _PostsScreenState();
// }

// class _PostsScreenState extends State<PostsScreen> {
//   final ApiService apiService = ApiService();
//   List<dynamic> posts = [];
//   bool isLoading = true;

//   @override
//   void initState() {
//     super.initState();
//     fetchPosts();
//   }

//   Future<void> fetchPosts() async {
//     try {
//       final fetchedPosts = await apiService.getPosts();
//       setState(() {
//         posts = fetchedPosts;
//         isLoading = false;
//       });
//     } catch (e) {
//       setState(() {
//         isLoading = false;
//       });
//       ScaffoldMessenger.of(context).showSnackBar(
//         SnackBar(content: Text('Failed to fetch posts: $e')),
//       );
//     }
//   }

//   @override
//   Widget build(BuildContext context) {
//     return Scaffold(
//       appBar: AppBar(
//         title: Text('Posts'),
//         actions: [
//           IconButton(
//             icon: Icon(Icons.add),
//             onPressed: () {
//               Navigator.pushNamed(context, '/add_post');
//             },
//           )
//         ],
//       ),
//       body: isLoading
//           ? Center(child: CircularProgressIndicator())
//           : ListView.builder(
//               itemCount: posts.length,
//               itemBuilder: (context, index) {
//                 final post = posts[index];
//                 return Card(
//                   margin: EdgeInsets.all(8.0),
//                   child: ListTile(
//                     title: Text(post['content']),
//                     subtitle: post['image'] != null
//                         ? Image.network(post['image'])
//                         : null,
//                   ),
//                 );
//               },
//             ),
//     );
//   }
// }
import 'package:flutter/material.dart';
import '../models/post.dart';
import '../services/api_service.dart';

class PostsScreen extends StatefulWidget {
  @override
  _PostsScreenState createState() => _PostsScreenState();
}

class _PostsScreenState extends State<PostsScreen> {
  final ApiService apiService = ApiService();
  List<Post> posts = []; // قائمة المنشورات
  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    fetchPosts();
  }

  // جلب المنشورات من API
  Future<void> fetchPosts() async {
    try {
      final fetchedPosts = await apiService.getPosts(); // جلب المنشورات
      setState(() {
        posts = fetchedPosts;
        isLoading = false;
      });
    } catch (e) {
      setState(() {
        isLoading = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Failed to fetch posts: $e')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Posts'),
        actions: [
          IconButton(
            icon: Icon(Icons.add),
            onPressed: () {
              Navigator.pushNamed(context, '/add_post'); // الانتقال إلى شاشة إضافة منشور
            },
          )
        ],
      ),
      body: isLoading
          ? Center(child: CircularProgressIndicator()) // حالة تحميل البيانات
          : ListView.builder(
              itemCount: posts.length, // عدد المنشورات
              itemBuilder: (context, index) {
                final post = posts[index]; // منشور من قائمة المنشورات
                return Card(
                  margin: EdgeInsets.all(8.0), // المارجن حول البطاقة
                  child: ListTile(
                    title: Text(post.content), // عرض محتوى المنشور
                    subtitle: post.imageUrl != null
                        ? Image.network(post.imageUrl!) // عرض الصورة إذا كانت موجودة
                        : null,
                    trailing: Column(
                      children: [
                        Text('${post.likesCount} Likes'), // عرض عدد الإعجابات
                        Text('${post.commentsCount} Comments'), // عرض عدد التعليقات
                      ],
                    ),
                  ),
                );
              },
            ),
    );
  }
}
