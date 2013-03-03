package Reporter;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLConnection;
import java.net.URLEncoder;
import java.io.ObjectOutputStream;
import java.io.FileOutputStream;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.ObjectInputStream;
import java.io.UnsupportedEncodingException;
import java.security.InvalidParameterException;
import java.util.ArrayList;
import java.util.List;

import java.io.StringWriter;
import java.io.PrintWriter;
import java.util.HashMap;
import java.util.Map;


//klasa Reportera - przyjmuje wyjatek i zglasza go jako bug report
public final class Reporter {
    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    public String getKey() {
        return key;
    }

    public void setKey(String key) {
        this.key = key;
    }

    String username = "";
    String password = "";
    String key = "";

    HashMap<String, String> parameters;
    /*
     * Web service URI
     */
    private String ws;
    public Reporter(String ws) {
        this.ws = ws;
        this.parameters = new HashMap<>();

        final Reporter report = this;
        // automatic error catching

        Thread.setDefaultUncaughtExceptionHandler(new Thread.UncaughtExceptionHandler() {
            @Override
            public void uncaughtException(Thread t, Throwable e) {
                if (!report.submit(e)){
                    report.queue(e);
                }
            }
        });

        // we ensure, that previously unsent exceptions are sent now
        try{
            this.ensurePending();
        }catch(IOException | ClassNotFoundException e) {
            System.out.println("Failed to submit pending exceptions.");
        }



    }
    /**
     * Checks if there are some not send exceptions
     * 
     * @return
     * @throws IOException
     * @throws ClassNotFoundException 
     */
    public boolean hasExceptionsPending() throws 
            IOException, 
            ClassNotFoundException
    {
        try{
            List <Throwable> exceptions = 
                    (List <Throwable>) new ObjectInputStream(
                        new FileInputStream("queue.data")
                    ).readObject();
            
        }catch(IOException exception){
            return false;
        }
        return true;
    }
     
    /**
     * Submits pending exceptions 
     * 
     * @return
     * @throws IOException
     * @throws ClassNotFoundException 
     */
    public boolean submitPending() throws 
            IOException, 
            ClassNotFoundException
    {
        List <Throwable> exceptions;
        try {
             exceptions = (List <Throwable>) new ObjectInputStream(new FileInputStream("queue.data")).readObject();
        } catch(FileNotFoundException exception) {
            return false;
        }
        
        //if properly read queue.data
        if (exceptions != null && exceptions instanceof ArrayList) {
            //try to submit

            for (Throwable e : exceptions){
                if (!this.submit(e)){
                    return false;
                }    
            }
        }
        //clear file
        new FileOutputStream("queue.data").write(new String().getBytes());

        return true;
    }
    
    /**
     * Submits exception 
     * 
     * @param e
     * @return
     * @throws UnsupportedEncodingException 
     */
    public boolean submit(Throwable e){

        if (e.getMessage() == null) {
            return false;
        }
            
        String className = Thread.currentThread().getStackTrace()[2].getClassName();
        int lineNumber = Thread.currentThread().getStackTrace()[2].getLineNumber();

        //dane do wyslania POSTem
        String data = "";
        try{
            data = URLEncoder.encode("version", "UTF-8") + "=" + URLEncoder.encode("1.113", "UTF-8");
            data += "&" + URLEncoder.encode("msg", "UTF-8") + "=" + URLEncoder.encode(e.getMessage(), "UTF-8");
            data += "&" + URLEncoder.encode("exception", "UTF-8") + "=" + URLEncoder.encode(this.exceptionToString(e), "UTF-8");
            data += "&" + URLEncoder.encode("class", "UTF-8") + "=" + URLEncoder.encode(className, "UTF-8");
            data += "&" + URLEncoder.encode("line", "UTF-8") + "=" + URLEncoder.encode(String.valueOf(lineNumber), "UTF-8");

            if (!this.username.isEmpty()) {
                this.parameters.put("auth[username]", this.username);
            }

            if (!this.password.isEmpty()) {
                this.parameters.put("auth[password]", this.password);
            }

            if (!this.key.isEmpty()) {
                this.parameters.put("param[license]", this.key);
            }

            for (String key : this.parameters.keySet()){
                data += "&" + URLEncoder.encode(key, "UTF-8") + "=" + URLEncoder.encode(this.parameters.get(key), "UTF-8");
            }
        }catch(UnsupportedEncodingException ex){
            return false;
        }

        try{
           
           URL url = new URL(this.ws);
           URLConnection conn = url.openConnection();
           
           conn.setDoOutput(true);
           OutputStreamWriter wr = new OutputStreamWriter(conn.getOutputStream());
           
           wr.write(data);
           wr.flush();

           BufferedReader rd = new BufferedReader(new InputStreamReader(conn.getInputStream()));
         
           String line;
           
           while ((line = rd.readLine()) != null) {
               if ("200 OK".equals(line)){
                   System.out.print("You just successfully submitted a bug report. \n");
               }else{
                   System.out.print(line + "\n");
               }
           }
           
           wr.close();
           rd.close();
        }catch(MalformedURLException urlException) {
            System.out.println("Malformed URL: " + urlException.getMessage());
            return false;
        }catch(IOException ioException) {
            System.out.println("IO exception: " + ioException.getMessage());
            return false;
        }

        return true;
    }

    /**
     * This methods allows to submit additional parameters
     * like license number or authorization data
     *
     * @param key
     * @param value
     */
    public void addParameter(String key, String value)
    {
        this.parameters.put("param[" +key+"]", value);
    }

    /**
     * This method is just an alias for hasExceptionsPending && submitPending
     *
     * @throws IOException
     * @throws ClassNotFoundException
     */
    public void ensurePending() throws IOException, ClassNotFoundException{
        if (this.hasExceptionsPending()) {
            this.submitPending();
        }
    }
    /**
     * Queues exception (appends to file)
     * 
     * @param Throwable e
     * @return boolean True on success
     * @throws IOException, ClassNotFoundException
     */ 
    public boolean queue(Throwable e) /*throws IOException, ClassNotFoundException*/ {
        List <Throwable> exceptions = new ArrayList<>(); //czy tutaj inicjalizacja nie popsuje
        
        try{
            exceptions =  (List <Throwable>) new ObjectInputStream(new FileInputStream("queue.data")).readObject();
        }catch(IOException emptyFile) {
            exceptions = new ArrayList<>();
        }catch(ClassNotFoundException notFound){
            return false;
        }
       
        //we append only the new exception
        exceptions.add(e);

        try{
            new ObjectOutputStream(new FileOutputStream("queue.data")).writeObject(exceptions);
        }catch(IOException fileNotFound){
            return false;
        }
        return true;
    }
    
    /**
     * Converts exception stack trace to string.
     * 
     * @param Throwable e
     * @return String 
     */ 
    protected String exceptionToString(Throwable e){
        StringWriter sw = new StringWriter();
        PrintWriter pw = new PrintWriter(sw);
        e.printStackTrace(pw);
        return sw.toString();
    }
}
