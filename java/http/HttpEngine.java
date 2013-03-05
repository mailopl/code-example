package http;
import java.net.*;
import java.io.*;
import java.util.*;


public class HttpEngine extends Thread {
     public void setDataDisplay(javax.swing.JTextArea dD){
        this.jTextArea1 = dD;

    }
    public HttpEngine(){
                super();
    }

    @Override
    public void run()  {

     ServerSocket serverSocket = null;
        //regular ports are 80 and 8080 but are used by apache or skype :(
        int port = 4444;

        try {
            serverSocket = new ServerSocket(port);
        } catch (IOException e) {

            this.jTextArea1.append("Port is busy: " + port + ".");
            System.exit(1);
        }

        while (true) {
            

           this.jTextArea1.append("Server set up on port " + port +", waiting for requests...\n");

            try {
                //Fetch client data
                Socket connectionSocket = serverSocket.accept();
                InetAddress client = connectionSocket.getInetAddress();
                

                //Fetch request headers
                BufferedReader input =
                        new BufferedReader(new InputStreamReader(connectionSocket.getInputStream()));

                //Prepare response headeres
                DataOutputStream output =
                        new DataOutputStream(connectionSocket.getOutputStream());

                String HTTP_method = input.readLine();
                if (HTTP_method.startsWith("GET")) {
                    String s = "HTTP/1.1 200 OK \r\n";
                    s = s + "Server: oddHttp \r\n"; //server name
                    s = s + "Connection: close\r\n"; //we can't handle persistent connections

                    char c = '\0';
                    String requestFile = "";
                    int i = 0;

                    //What's the name of requested file ?
                    while((c = HTTP_method.charAt(5+i)) != ' '){
                        requestFile +=c;
                        ++i;
                    }
                    //if file exists, send file contents and change encoding: todo

                    String file_extension = requestFile.substring(requestFile.lastIndexOf(".")+1);

                    //default content type
                    String contentType = "text/plain";

                    Map map = new HashMap();
                        map.put("png", "image");
                        map.put("jpg" , "image");
                        map.put("gif"    , "image");
                        map.put("jpeg"    , "image");
                        map.put("html", "text/html");
                        map.put("htm", "text/html");
                        map.put("php", "text/html");
                        map.put("txt", "text/plain");
                        map.put("css", "text/css");
                        map.put("js", "application/js");

                    if (map.containsKey(file_extension)){
                        contentType =
                                map.get(file_extension).toString();
                    }

                    s = s + "Content-type: " + contentType + "\r\n";
                    s = s + "\r\n"; //EO headers

                     this.jTextArea1.append("FILE: " + requestFile + "\n");



                    if(file_extension.equals("php")){

                        String line;
                        //vulnerability
                        //load settings from some nice tiny file
                        Process p = Runtime.getRuntime().exec("c:\\wamp\\bin\\php\\php5.3.0\\php.exe -f c:\\"+requestFile);
                        BufferedReader fileInput =
                                new BufferedReader(new InputStreamReader(p.getInputStream()));
                        while ((line = fileInput.readLine()) != null) {
                            output.writeBytes(line);
                        }
                        fileInput.close();
                    }else{
						output.writeBytes(s);
                    }
                     this.jTextArea1.append("Response sent\n");
                    output.close();
                }

            } catch (Exception e) { //catch any errors, and print them
                this.jTextArea1.append("\nError:" + e.getMessage());
            }

        }

    }
    public void acceptRequests(){


    }

    public boolean fileExists(String path){
        File f = new File(path);
        return f.exists();
    }
    public void messageLog(String msg){
        this.jTextArea1.append(msg);
    }
    public ArrayList constructResponseHeaders(){
        return new ArrayList();
    }
    public ArrayList parseClientHeaders() throws IOException{

        BufferedReader input =
                        new BufferedReader(new InputStreamReader(this.serverSocket.accept().getInputStream()));
        String line="";
        ArrayList al = new ArrayList();

        while ((line = input.readLine()) != null) {
           al.add(line);
        }

        return al;
    }
    public void sendResponseHeaders(ArrayList headers) throws IOException{ 
        Iterator<String> itr = headers.iterator();
        while (itr.hasNext()) {
          this.out.writeBytes(itr.next());
        }
    }
    public void sendResponse(String data) throws IOException{
        this.out.writeBytes(data);
        
    }
    public void processPHPFile(){


    }


    public boolean createListenSocket(int port) throws IOException{
        this.serverSocket = null;

        try {
            serverSocket = new ServerSocket(port);
        } catch (IOException e) {
            messageLog("Port is busy: " + port + ".");
            System.exit(1);
        }
        return false;
    }

    /*

     Map headers = new HashMap();
     headers.put("201", "HTTP 201 OK");
     headers.put("404","");
     headers.put("501","");
     headers.put("301","");

     Map contentTypes = new HashMap();
     contentTypes.put("text", "text/plain");
     //...

     ServerSocket mainSocket = createListemSocket(80);
     while(true){
        messageLog("Server ready...");
        ArrayList clientHeaders = parseClientHeaders(); //ip, compression, file name, location ...


        if (fileExists(clientHeaders["file"])){
            switch (clientHeaders("fileExt")){
                case "php":
                    sendResponseHeaders(ArrayList={HTTP 201 OK, bla bla bla});
                    sendResponse(processPHPFile(clientHeaders["filePath"]));
                break;

                case "html":
                    sendResponseHeaders(ArrayList={HTTP 201 OK, ContentType text/html. bla bla bla};
                break;
            }
        }else{
            sendResponseHeaders(ArrayList={NOT FOUND 404});
        }

     */

    private DataOutputStream out ;
     private javax.swing.JTextArea jTextArea1;
     private ServerSocket  serverSocket;
}
