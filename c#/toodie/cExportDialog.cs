using System.Drawing;
using System.Windows.Forms;
using System.Diagnostics;
using System.Data;
using System.IO;
using System;
using System.Data.SqlClient;
using System.Data.SqlServerCe;
using sharpPDF;
using sharpPDF.Bookmarks;
using sharpPDF.Collections;
using sharpPDF.Elements;
using sharpPDF.Enumerators;
using sharpPDF.Exceptions;
using sharpPDF.Fonts;
using sharpPDF.PDFControls;
using sharpPDF.Tables;

namespace Toodie
{
    public class ExportBox : System.Windows.Forms.Form
    {
        private RadioButton radioButton1;
        private RadioButton radioButton2;
        public RadioButton radioButton3;
        public RadioButton radioButton4;
        private RadioButton radioButton5;
        private RadioButton radioButton6;
        private Button button1;
        private ListView parentListView, projectsListView;
        private TabControl tabControl;
        private RadioButton radioButton7;
        private GroupBox groupBox1;
        private GroupBox groupBox2;
        private string connectionString;
        public int actualProject;
        public int actualFocus;
        private RadioButton radioButton8;
        private System.Resources.ResourceManager rm;

        private System.ComponentModel.Container components = null;

        public void LocalizeUI(){
            this.groupBox1.Text = rm.GetString("exportFormat");
            this.groupBox2.Text = rm.GetString("exportRange");
            //this.radioButton8.Text = rm.GetString("toodieDatabase");
            this.radioButton3.Text = rm.GetString("ActProjActFocus");
            this.radioButton4.Text = rm.GetString("ActProjAllFocus");
            this.radioButton5.Text = rm.GetString("AllProjActFocus");
            this.radioButton6.Text = rm.GetString("AllProjAllFocus");
            this.button1.Text = this.Text = rm.GetString("export");
        }
        public ExportBox()
        {
            InitializeComponent();
            this.radioButton2.Checked=
            this.radioButton3.Checked = true;          
            
        }

        protected override void Dispose(bool disposing)
        {
            if (disposing)
            {
                if (components != null)
                {
                    components.Dispose();
                }
            }
            base.Dispose(disposing);
        }

       
        #region Windows Form Designer generated code
        private void InitializeComponent()
        {
            this.radioButton1 = new System.Windows.Forms.RadioButton();
            this.radioButton2 = new System.Windows.Forms.RadioButton();
            this.radioButton3 = new System.Windows.Forms.RadioButton();
            this.radioButton4 = new System.Windows.Forms.RadioButton();
            this.radioButton5 = new System.Windows.Forms.RadioButton();
            this.radioButton6 = new System.Windows.Forms.RadioButton();
            this.button1 = new System.Windows.Forms.Button();
            this.radioButton7 = new System.Windows.Forms.RadioButton();
            this.groupBox1 = new System.Windows.Forms.GroupBox();
            this.radioButton8 = new System.Windows.Forms.RadioButton();
            this.groupBox2 = new System.Windows.Forms.GroupBox();
            this.groupBox1.SuspendLayout();
            this.groupBox2.SuspendLayout();
            this.SuspendLayout();
            // 
            // radioButton1
            // 
            this.radioButton1.AutoSize = true;
            this.radioButton1.Location = new System.Drawing.Point(14, 20);
            this.radioButton1.Name = "radioButton1";
            this.radioButton1.Size = new System.Drawing.Size(51, 17);
            this.radioButton1.TabIndex = 0;
            this.radioButton1.TabStop = true;
            this.radioButton1.Text = "HTML";
            this.radioButton1.UseVisualStyleBackColor = true;
            // 
            // radioButton2
            // 
            this.radioButton2.AutoSize = true;
            this.radioButton2.Location = new System.Drawing.Point(14, 41);
            this.radioButton2.Name = "radioButton2";
            this.radioButton2.Size = new System.Drawing.Size(44, 17);
            this.radioButton2.TabIndex = 1;
            this.radioButton2.TabStop = true;
            this.radioButton2.Text = "PDF";
            this.radioButton2.UseVisualStyleBackColor = true;
            // 
            // radioButton3
            // 
            this.radioButton3.AutoSize = true;
            this.radioButton3.Location = new System.Drawing.Point(12, 14);
            this.radioButton3.Name = "radioButton3";
            this.radioButton3.Size = new System.Drawing.Size(174, 17);
            this.radioButton3.TabIndex = 4;
            this.radioButton3.TabStop = true;
            this.radioButton3.Text = "Actual project and actual focus";
            this.radioButton3.UseVisualStyleBackColor = true;
            // 
            // radioButton4
            // 
            this.radioButton4.AutoSize = true;
            this.radioButton4.Location = new System.Drawing.Point(12, 36);
            this.radioButton4.Name = "radioButton4";
            this.radioButton4.Size = new System.Drawing.Size(166, 17);
            this.radioButton4.TabIndex = 5;
            this.radioButton4.TabStop = true;
            this.radioButton4.Text = "Actual project and all focuses";
            this.radioButton4.UseVisualStyleBackColor = true;
            // 
            // radioButton5
            // 
            this.radioButton5.AutoSize = true;
            this.radioButton5.Location = new System.Drawing.Point(12, 59);
            this.radioButton5.Name = "radioButton5";
            this.radioButton5.Size = new System.Drawing.Size(162, 17);
            this.radioButton5.TabIndex = 6;
            this.radioButton5.TabStop = true;
            this.radioButton5.Text = "All projects with actual focus";
            this.radioButton5.UseVisualStyleBackColor = true;
            // 
            // radioButton6
            // 
            this.radioButton6.AutoSize = true;
            this.radioButton6.Location = new System.Drawing.Point(12, 82);
            this.radioButton6.Name = "radioButton6";
            this.radioButton6.Size = new System.Drawing.Size(36, 17);
            this.radioButton6.TabIndex = 7;
            this.radioButton6.TabStop = true;
            this.radioButton6.Text = "All";
            this.radioButton6.UseVisualStyleBackColor = true;
            // 
            // button1
            // 
            this.button1.Location = new System.Drawing.Point(182, 200);
            this.button1.Name = "button1";
            this.button1.Size = new System.Drawing.Size(68, 22);
            this.button1.TabIndex = 8;
            this.button1.Text = "Export";
            this.button1.UseVisualStyleBackColor = true;
            this.button1.Click += new System.EventHandler(this.button1_Click);
            // 
            // radioButton7
            // 
            this.radioButton7.AutoSize = true;
            this.radioButton7.Location = new System.Drawing.Point(86, 21);
            this.radioButton7.Name = "radioButton7";
            this.radioButton7.Size = new System.Drawing.Size(44, 17);
            this.radioButton7.TabIndex = 10;
            this.radioButton7.TabStop = true;
            this.radioButton7.Text = "CSV";
            this.radioButton7.UseVisualStyleBackColor = true;
            // 
            // groupBox1
            // 
            this.groupBox1.Controls.Add(this.radioButton8);
            this.groupBox1.Controls.Add(this.radioButton7);
            this.groupBox1.Controls.Add(this.radioButton2);
            this.groupBox1.Controls.Add(this.radioButton1);
            this.groupBox1.Location = new System.Drawing.Point(6, 13);
            this.groupBox1.Name = "groupBox1";
            this.groupBox1.Size = new System.Drawing.Size(244, 66);
            this.groupBox1.TabIndex = 11;
            this.groupBox1.TabStop = false;
            this.groupBox1.Text = "Select export format";
            // 
            // radioButton8
            // 
            this.radioButton8.AutoSize = true;
            this.radioButton8.Location = new System.Drawing.Point(86, 41);
            this.radioButton8.Name = "radioButton8";
            this.radioButton8.Size = new System.Drawing.Size(72, 17);
            this.radioButton8.TabIndex = 11;
            this.radioButton8.TabStop = true;
            this.radioButton8.Text = "Excel XML";
            this.radioButton8.UseVisualStyleBackColor = true;
            // 
            // groupBox2
            // 
            this.groupBox2.Controls.Add(this.radioButton6);
            this.groupBox2.Controls.Add(this.radioButton5);
            this.groupBox2.Controls.Add(this.radioButton4);
            this.groupBox2.Controls.Add(this.radioButton3);
            this.groupBox2.Location = new System.Drawing.Point(5, 85);
            this.groupBox2.Name = "groupBox2";
            this.groupBox2.Size = new System.Drawing.Size(245, 109);
            this.groupBox2.TabIndex = 12;
            this.groupBox2.TabStop = false;
            this.groupBox2.Text = "Select export range";
            // 
            // ExportBox
            // 
            this.AutoScaleBaseSize = new System.Drawing.Size(5, 14);
            this.ClientSize = new System.Drawing.Size(252, 225);
            this.Controls.Add(this.groupBox2);
            this.Controls.Add(this.groupBox1);
            this.Controls.Add(this.button1);
            this.Font = new System.Drawing.Font("Tahoma", 8.25F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedToolWindow;
            this.Name = "ExportBox";
            this.ShowIcon = false;
            this.ShowInTaskbar = false;
            this.Text = "Toodie Export";
            this.groupBox1.ResumeLayout(false);
            this.groupBox1.PerformLayout();
            this.groupBox2.ResumeLayout(false);
            this.groupBox2.PerformLayout();
            this.ResumeLayout(false);

        }
        #endregion
        public void setListView(ListView lv)
        {
            this.parentListView = lv;
        }
        public void setProjectsListView(ListView lv)
        {
            this.projectsListView = lv;
        }
        public void setFocusTab(TabControl tc)
        {
            this.tabControl = tc;
        }
        public void setConnectionString(string str){
            this.connectionString = str;
        }
        public void setResMan(ref System.Resources.ResourceManager rm)
        {
            this.rm = rm;
            LocalizeUI();
        }
        private string clearTails(string str)
        {
            str = str.Replace("ą", "a");
            str = str.Replace("ż", "z");
            str = str.Replace("ź", "z");
            str = str.Replace("ć", "c");
            str = str.Replace("ę", "e");
            str = str.Replace("ń", "n");
            str = str.Replace("ł", "l");
            str = str.Replace("ó", "o");
            return str;
            
        }

        private ListViewItem[] GetDataToProceed(int checkboxId, ref int pagesCount)
        {
            string qAllProjectsAllFocuses =
                "SELECT projects.title AS ProjectTitle, task_list.date, task_list.status, " +
                "task_list.title AS TaskTitle FROM projects INNER JOIN " +
                "task_list ON projects.id = task_list.project_id " +
                "ORDER BY projects.title, task_list.id";
            
            string qAllProjectsActualFocus =
                "SELECT projects.title AS ProjectTitle, task_list.date, task_list.status, " +
                "task_list.title AS TaskTitle FROM projects INNER JOIN " +
                "task_list ON projects.id = task_list.project_id WHERE " +
                "task_list.focus_id=@Focus ORDER BY projects.title, task_list.id";
           
            string qActualProjectAllFocuses =
                "SELECT projects.title AS ProjectTitle, task_list.date, task_list.status, " +
                "task_list.title AS TaskTitle FROM projects INNER JOIN " +
                "task_list ON projects.id = task_list.project_id WHERE " +
                "task_list.project_id=@Project ORDER BY projects.title, task_list.id";
            
            string qActualProjectActualFocus =
                "SELECT projects.title AS ProjectTitle, task_list.date, task_list.status, " +
                "task_list.title AS TaskTitle FROM projects INNER JOIN " +
                "task_list ON projects.id = task_list.project_id WHERE " +
                "task_list.focus_id=@Focus AND task_list.project_id=@Project "+ 
                "ORDER BY projects.title, task_list.id";

            System.Collections.ArrayList al = new System.Collections.ArrayList();
            //MessageBox.Show(connectionString);
            using (SqlCeConnection connection = new SqlCeConnection(connectionString))
            {
                connection.Open();
                SqlCeCommand cmd = new SqlCeCommand("", connection);

                switch (checkboxId)
                {
                    case 6:
                        cmd.CommandText = qAllProjectsAllFocuses;
                        break;

                    case 5:
                        cmd.CommandText = qAllProjectsActualFocus;
                        cmd.Parameters.Add("@Focus", SqlDbType.Int);
                        cmd.Parameters["@Focus"].Value = actualFocus; //?
                        break;
                    case 4:
                        cmd.CommandText = qActualProjectAllFocuses;
                        cmd.Parameters.Add("@Project", SqlDbType.Int);
                        cmd.Parameters["@Project"].Value = actualProject; //?
                        break;
                    case 3:
                        cmd.CommandText = qActualProjectActualFocus;
                        cmd.Parameters.Add("@Focus", SqlDbType.Int);
                        cmd.Parameters["@Focus"].Value = actualFocus; //?

                        cmd.Parameters.Add("@Project", SqlDbType.Int);
                        cmd.Parameters["@Project"].Value = actualProject; //?
                        break;
                }
                SqlCeResultSet resultSet = cmd.ExecuteResultSet(ResultSetOptions.Scrollable | ResultSetOptions.Updatable);
                SqlCeDataReader rdr = cmd.ExecuteReader();
                pagesCount = (int) rdr.RecordsAffected / 20 + 2;
                while (rdr.Read())
                {
                    al.Add((ListViewItem)new ListViewItem(
                        new String[]{
                            rdr["TaskTitle"].ToString(), 
                            rdr["ProjectTitle"].ToString(), 
                            rdr["date"].ToString(),
                            rdr["status"].ToString()
                        }
                        ));
                }
                rdr.Close();

            }
            return (ListViewItem[]) al.ToArray(typeof(ListViewItem));
        }
        private pdfDocument Export2PDF()
        {
            pdfDocument myDoc = new pdfDocument("Toodie Task List", "You");
            int actualPage = 0;
            int totalPages = 0;
            int selectedOption = 0;
            int j = 0;
            pdfPage[] pages; //pages array, which needs to be initialized after RecordsAffected
            
            if (radioButton3.Checked)
            {
                selectedOption = 3;
            }
            else if (radioButton4.Checked)
            {
                selectedOption = 4;
            }
            else if(radioButton5.Checked)
            {
                selectedOption = 5;
            }
            else
            {
                selectedOption = 6;
            }

            //Get data basing on option box
            ListViewItem[] Data = GetDataToProceed(selectedOption, ref totalPages);            
            pages = new pdfPage[totalPages];
            
            //Add pages, basing on page count from getDataToProceed function
            for (int i = 0; i < totalPages; ++i)
            {
                pages[i] = myDoc.addPage();
            }
            
            //Data output part
            foreach (ListViewItem itm in Data)
            {
                pages[actualPage].addText(
                    "|P|: " + itm.SubItems[1].Text + 
                    "  |D|: " + itm.SubItems[2].Text, 
                    30, 
                    760 - (j + 1) * 30, 
                    myDoc.getFontReference("Helvetica"), 
                    10, 
                    pdfColor.LightGray);
                
                pages[actualPage].addText(
                    clearTails(itm.SubItems[0].Text), 30, 
                    715 - (j * 30), 
                    myDoc.getFontReference("Helvetica"), 
                    10, 
                    itm.SubItems[3].Text == "1" ? pdfColor.Green : pdfColor.LightBlue);
                
                //After we add 20 items, switch to next page
                ++j;
                if (j % 20 == 0)
                {
                    ++actualPage;
                    j = 0;
                }
                
            }

            return myDoc;
        }
        private string Export2HTML()
        {
            string content = "";
            int selectedOption = 0;
            int totalPages = 0;
            try
            {
                if (radioButton3.Checked)
                {
                    selectedOption = 3;
                }
                else if (radioButton4.Checked)
                {
                    selectedOption = 4;
                }
                else if (radioButton5.Checked)
                {
                    selectedOption = 5;
                }
                else
                {
                    selectedOption = 6;
                }

                content += "<head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /><style type=\"text/css\">body{font-family:verdana;color:gray;font-size:11px;}i{color:lightblue;}</style></head><h2>";
                content += this.projectsListView.Items[actualProject].SubItems[0].Text ="</h2>";
                    
                ListViewItem[] Data = GetDataToProceed(selectedOption, ref totalPages);            
                foreach (ListViewItem itm in Data)
                {
                    content += "<i>|D|: " + itm.SubItems[1].Text + "</i><br />";
                    content += itm.SubItems[0].Text + "<hr size=1/>";
                }                
            }
            catch (Exception exc)
            {
                Console.WriteLine(exc.Message);
                MessageBox.Show(rm.GetString("error1"));
            }
            return content;
        }


        private string Export2Excel()
        {
            string content = "";
            int selectedOption = 0;
            int totalPages = 0;
            try
            {
                if (radioButton3.Checked)
                {
                    selectedOption = 3;
                }
                else if (radioButton4.Checked)
                {
                    selectedOption = 4;
                }
                else if (radioButton5.Checked)
                {
                    selectedOption = 5;
                }
                else
                {
                    selectedOption = 6;
                }

                ListViewItem[] Data = GetDataToProceed(selectedOption, ref totalPages);
                content += "Date;Content;Project";
                foreach (ListViewItem itm in Data)
                {
                    content += itm.SubItems[2].Text + ";";
                    content += clearTails(itm.SubItems[0].Text) + ";";
                    content += itm.SubItems[1].Text  + "\n";
                }
            }
            catch (Exception exc)
            {
                Console.WriteLine(exc.Message);
                MessageBox.Show(rm.GetString("error1"));
            }
            return content;
        }
        private string Export2ExcelXML()
        {
            string content = "";
            int selectedOption = 0;
            int totalPages = 0;
            try
            {
                if (radioButton3.Checked)
                {
                    selectedOption = 3;
                }
                else if (radioButton4.Checked)
                {
                    selectedOption = 4;
                }
                else if (radioButton5.Checked)
                {
                    selectedOption = 5;
                }
                else
                {
                    selectedOption = 6;
                }

                ListViewItem[] Data = GetDataToProceed(selectedOption, ref totalPages);
                content += Properties.Settings.Default.xmlHeader;

                foreach (ListViewItem itm in Data)
                {
                    content += "<ss:Row>" +
                "<ss:Cell>" +
                    "<ss:Data ss:Type=\"String\">" + itm.SubItems[2].Text+"</ss:Data>" +
                "</ss:Cell>" +
              "  <ss:Cell>" +
                 "   <ss:Data ss:Type=\"String\">"+clearTails(itm.SubItems[0].Text)+"</ss:Data>" +
                "</ss:Cell>" +
                "<ss:Cell>" +
                    "<ss:Data ss:Type=\"String\">" + itm.SubItems[1].Text + "</ss:Data>" +
                "</ss:Cell>" +
            "</ss:Row>";
                   // content += itm.SubItems[2].Text + ";";
                    //content += clearTails(itm.SubItems[0].Text) + ";";
                    //content += itm.SubItems[1].Text + "\n";
                }
                content+= 
                    " </ss:Table>"+
                    "</ss:Worksheet>" +
                    "</ss:Workbook>";
            }
            catch (Exception exc)
            {
                Console.WriteLine(exc.Message);
                MessageBox.Show(rm.GetString("error1"));
            }
            return content;
        }
        private void button1_Click(object sender, System.EventArgs e)
        {
            SaveFileDialog saveDialog = new SaveFileDialog();
            saveDialog.AddExtension = true;

            if (actualProject <0) actualProject = 0;
            if (actualProject > this.projectsListView.Items.Count) actualProject= 0;
            
            saveDialog.FileName = this.projectsListView.Items[actualProject].SubItems[0].Text;
            saveDialog.Title = rm.GetString("saveTasks");
            //to PDF
            if (radioButton2.Checked)
            {
                pdfDocument myDocument = Export2PDF();                
                saveDialog.DefaultExt = ".pdf";                
                saveDialog.Filter = rm.GetString("files") + " PDF (*.pdf)|*.PDF";
                
                if (saveDialog.ShowDialog() == DialogResult.OK)
                {
                    StreamWriter sw = new StreamWriter(saveDialog.FileName, false, System.Text.Encoding.UTF8);
                    myDocument.createPDF(sw.BaseStream);
                }                
            }
            //to HTML
            else if (radioButton1.Checked)
            {
                saveDialog.DefaultExt = ".htm";
                saveDialog.Filter = rm.GetString("files") + " HTML (*.htm)|*.HTM";
                string htmlDocument = Export2HTML();
                if (saveDialog.ShowDialog() == DialogResult.OK)
                {
                    StreamWriter sw = new StreamWriter(saveDialog.FileName, false, System.Text.Encoding.UTF8);
                    sw.Write(htmlDocument);
                    sw.Close();
                    MessageBox.Show(rm.GetString("completed"), "HTML", MessageBoxButtons.OK, MessageBoxIcon.Information);
                }
            }
            //to CSV
            else if (radioButton7.Checked)
            {
                saveDialog.DefaultExt = ".csv";
                saveDialog.Filter = rm.GetString("files") + " CSV (*.csv)|*.csv";
                string htmlDocument = Export2Excel();
                if (saveDialog.ShowDialog() == DialogResult.OK)
                {
                    StreamWriter sw = new StreamWriter(saveDialog.FileName, false, System.Text.Encoding.UTF8);
                    sw.Write(htmlDocument);
                    sw.Close();
                    MessageBox.Show(rm.GetString("completed"), "CSV", MessageBoxButtons.OK, MessageBoxIcon.Information);
                }
            }
            //to Excel XLS XML
            else if (radioButton8.Checked)
            {
                saveDialog.DefaultExt = ".xls";
                saveDialog.Filter = rm.GetString("files") + " XLS (*.xls)|*.xls";
                string htmlDocument = Export2ExcelXML();
                if (saveDialog.ShowDialog() == DialogResult.OK)
                {
                    StreamWriter sw = new StreamWriter(saveDialog.FileName, false, System.Text.Encoding.UTF8);
                    sw.Write(htmlDocument);
                    sw.Close();
                    MessageBox.Show(rm.GetString("completed"), "Excel XLS XML", MessageBoxButtons.OK, MessageBoxIcon.Information);
                }
            }
            

            this.Hide();
        }
    }
}
