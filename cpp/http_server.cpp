#include <stdafx.h>
#include <winsock2.h>
#include <ws2tcpip.h>
#include <windows.h>
#include <iostream>
#include <cassert>
#include <string>
#include <fstream>


/* Server configuration, should be read from file. */
const std::string WWW_DIR =  "c:\\www\\";


/* Returns file as a string */ 
std::string read_file(std::string file){
	std::ifstream			out(file.c_str(), std::ios::binary );
	std::ifstream::pos_type size;
	char*					memblock;
	if (out.is_open()){
		out.seekg (0, std::ios::end);
		size = out.tellg(); 
		out.seekg (0, std::ios::beg);

		memblock = new char [size];
		out.read (memblock, size);
		
		out.close();

		std::string ret;
		ret.assign( memblock, size);

		return ret;
  }else{
	  return "";
  }

}

/* Returns filename based on http request */
std::string http_get_requested_file(char * buffer){
	std::string data(buffer);
	size_t slash_pos = data.find_first_of('/');
	size_t space_pos = data.find_first_of(' ', slash_pos+1);
	return data.substr(slash_pos+1, space_pos-slash_pos);
}

/* Returns operation type based on http request */ 
std::string http_operation_type(char * buffer){
	std::string data(buffer);
	size_t slash_pos = data.find_first_of('/');

	if (slash_pos != std::string::npos)
		return data.substr(0, slash_pos-1);
	else 
		return "";	
}

/* Returns file content based on file extension */
std::string http_file_extension_to_content_type(std::string filename){
	std::string ext = filename.substr(filename.find_last_of(".")+1, 4);
	if (ext == "html" || ext =="htm" || ext== "txt"){
		return "Content-type: text/html\r\n";
	}else if(ext == "png"){
		return "Content-type: image/jpeg\r\n";
	}else if(ext == "jpg"){
		return "Content-type: image/png\r\n";
	}else{
		return "Content-type: text/html\r\n";
	}
}
/* Shows 404 error */ 
char * http_404(){
	std::cout <<"404 ERROR \n";
	return 
		"HTTP/1.1 404 Bad Request\r\n"
		"Content-Type: text/html\r\n"
		"Content-Length: 111\r\n\r\n"

		"<html><body>"
		"<h2>404: Not Found</h2>"
		"File is not present at this location"
		"</body></html>";
}

int main() {
	std::cout<<
		"http server                   \n"
		"\nlog: \n\n"
		"type     |     file     \n"
		"-------------------------\n";
    WSADATA wsa;

    assert( WSAStartup( MAKEWORD( 2, 2 ), &wsa ) == 0 );

    addrinfo *res = NULL;
    addrinfo hints;

    ZeroMemory( &hints, sizeof( hints ) );

    hints.ai_family = AF_INET;
    hints.ai_socktype = SOCK_STREAM;
    hints.ai_protocol = IPPROTO_TCP;
    hints.ai_flags = AI_PASSIVE;

    assert( getaddrinfo( NULL, "80", &hints, &res ) == 0 );

    SOCKET s = socket( res->ai_family, res->ai_socktype, res->ai_protocol );

    assert( s != INVALID_SOCKET );
    assert( bind( s, res->ai_addr, (int)res->ai_addrlen ) != SOCKET_ERROR );
    assert( listen( s, SOMAXCONN ) != SOCKET_ERROR );
	
	/* Main server loop */
	std::string method ="";
	char buffer[512];
	int bytes;
	std::string strBuffer="";
	std::string filename="";
	std::string output=""; 

	while(1){
		SOCKET client = accept( s, NULL, NULL );
		assert( client != INVALID_SOCKET );

		/* Read user request into the buffer */
		
		bytes = recv( client, buffer, 512, 0 );
		method = http_operation_type(buffer);

		if (method == "GET" || method == "POST" || method== "HEAD"){ /* supported methods */
			std::cout <<method;

			strBuffer=buffer;

		

			/* User wants to receive some file, let's find out which one. */
			filename = http_get_requested_file(buffer);

			std::cout <<"     " <<filename;
			if (method != "HEAD"){
				output = read_file(WWW_DIR + filename);
				
				
				/* Prepare response, if length of file is 0, file wasn't found */ 

				if (output.length() < 1){
					output = http_404();
				}else{

				/* Else, send response with file content and headers */ 
				output =
					"HTTP/1.1 200 OK\r\n"
					"Connection: close\r\n" +
					http_file_extension_to_content_type( filename ) +
					"\r\n" +
					output;
				}
			}else{
				output =
					"HTTP/1.1 200 OK\r\n"
					"Connection: close\r\n" +
					http_file_extension_to_content_type( filename ) +
					"\r\n";
			
			}
				assert( send( client, output.c_str(), output.length(), 0 ) > 0 );
				std::cout <<"  \n";
			

		}
		else{
			std::cout <<"Unknown method: " <<method <<"'\n";
			send( client, http_404(), strlen(http_404()), 0 );

		}

		

	  
		assert( shutdown( client, SD_BOTH ) != SOCKET_ERROR );
		closesocket( client );
		//WSACleanup();
	}
   
	system("pause");
	return 0;
}