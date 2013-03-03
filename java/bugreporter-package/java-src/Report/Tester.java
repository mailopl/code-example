/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package Report;
import java.io.File;
import java.io.IOException;

public class Tester {
    
    public static int divide(int a, int b) throws NumberFormatException{        
       return a/b;
    }
    
    public static void read() throws IOException{
        File f = new File("non.existent.file");
    }
    
    public static void arrayException() throws RuntimeException{
        int[] aArray = new int[2];
        aArray[1] = 3;
        aArray[9] = 9;
    }
}
