import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../models/post.dart';
class ApiService {
  final String baseUrl =
      "http://192.168.43.161:8000/api"; // غيّر العنوان حسب السيرفر

  Future<String?> login(String email, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/login'),
      body: {
        'email': email,
        'password': password,
      },
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      final token = data['token'];

      // تخزين التوكن محليًا
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString('token', token);

      return token;
    } else {
      throw Exception('Failed to login');
    }
  }

  Future<void> signup(String name, String email, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/signup'),
      body: {
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': password,
      },
    );

    if (response.statusCode != 201) {
      throw Exception('Failed to signup');
    }
  }

//   Future<List<dynamic>> getPosts() async {
//     final prefs = await SharedPreferences.getInstance();
//     final token = prefs.getString('token') ?? '';

//     final response = await http.get(
//       Uri.parse('$baseUrl/posts'),
//       headers: {
//         'Authorization': 'Bearer $token',
//       },
//     );

//     if (response.statusCode == 200) {
//       final data = json.decode(response.body);
//       return data['posts'];
//     } else {
//       throw Exception('Failed to fetch posts');
//     }
//   }
 Future<List<Post>> getPosts() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token') ?? ''; // الحصول على التوكن من SharedPreferences

    final response = await http.get(
      Uri.parse('$baseUrl/posts'),
      headers: {
        'Authorization': 'Bearer $token', // إضافة التوكن في الهيدر
      },
    );

    if (response.statusCode == 200) {
      // تحويل JSON إلى قائمة من الكائنات Post
      final data = json.decode(response.body)['posts'] as List;

      // تحويل كل عنصر في القائمة إلى كائن Post
      return data.map((json) => Post.fromJson(json)).toList();
    } else {
      throw Exception('Failed to fetch posts');
    }
  }

  Future<void> addPost(String content, String? imagePath) async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token') ?? '';

    var request = http.MultipartRequest('POST', Uri.parse('$baseUrl/posts'));
    request.headers['Authorization'] = 'Bearer $token';
    request.fields['content'] = content;

    if (imagePath != null) {
      request.files.add(await http.MultipartFile.fromPath('image', imagePath));
    }

    final response = await request.send();

    if (response.statusCode != 201) {
      throw Exception('Failed to create post');
    }
  }
}
