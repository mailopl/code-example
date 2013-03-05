using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Drawing;
using System.Windows.Forms;
using System.Data;
using System.IO;
using System.Data.SqlClient;
using System.Data.SqlServerCe;
using System.Resources;
using System.Globalization;
using System.Xml;
using System.Threading;
using System.Collections;
using System.Text.RegularExpressions;


namespace Toodie
{
	public partial class MainForm : Form
	{
        string connectionString = @"Data Source = "+Application.StartupPath+"\\Resources\\base.sdf";
        
        private ResourceManager rm;
        
        public MainForm()
        {
           
           InitializeComponent();
           
           this.DoubleBuffered = true;
           this.SetStyle(
  				ControlStyles.AllPaintingInWmPaint |
  				ControlStyles.UserPaint |
  				ControlStyles.OptimizedDoubleBuffer|
  				ControlStyles.DoubleBuffer,true
  			);
        
            //-------------------------
            //Internationalization
            //-------------------------
            rm = new ResourceManager("Toodie.Localization", System.Reflection.Assembly.GetExecutingAssembly());
      
            Thread.CurrentThread.CurrentCulture =
                Thread.CurrentThread.CurrentUICulture = 
                    new CultureInfo(Properties.Settings.Default.Language);
            
            LocalizeUI();
          
            //-------------------------
            // Predefined actions
            //-------------------------
            this.toolStripTextBox1.KeyPress += new System.Windows.Forms.KeyPressEventHandler(onEnter_renameProject);
            this.toolStripTextBox2.KeyPress += new System.Windows.Forms.KeyPressEventHandler(onEnter_addProject);
            this.toolStripTextBox3.KeyPress += new System.Windows.Forms.KeyPressEventHandler(onEnter_search);
            this.listView1.KeyDown += new System.Windows.Forms.KeyEventHandler(onKey_delete);
			this.listView1.MouseClick += new System.Windows.Forms.MouseEventHandler(listView1_onMouseClick);
            this.dateTime.ValueChanged+= new EventHandler(task_dateChanged);
            this.dateTime.Size = new Size(100,20);
            this.dateTime.Format = DateTimePickerFormat.Short;
            
            foreach (ToolStripItem itm in languageToolStripMenuItem.DropDownItems)
            {
                itm.Click += new EventHandler(changeLanguage);
            }
            //-------------------------
            // Images
            //-------------------------
           
            ImageList list = new ImageList();
                list.Images.Add(Image.FromFile(Application.StartupPath+"\\Resources\\document.gif"));
                list.Images.Add(Image.FromFile(Application.StartupPath + "\\Resources\\document_big.gif"));
                list.Images.Add(Image.FromFile(Application.StartupPath + "\\Resources\\yard.gif"));
            
            this.listView1.SmallImageList = list;
            this.listView2.SmallImageList = list; 
            this.listView1.LabelEdit = true;
            this.listView1.ColumnClick += new ColumnClickEventHandler(ColumnClick);

            //-------------------------
            // A bunch of settings related things 
            //-------------------------
            if (Properties.Settings.Default.hideNotes)
            {
                this.richTextBox1.Hide();
                this.listView1.Height += 90;
                this.noteInformationToolStripMenuItem.Checked = true;
            } 
            if (Properties.Settings.Default.openLatestDB)
            {
                this.alwaysOpenLatestDBToolStripMenuItem.Checked = true;
                this.noteInformationToolStripMenuItem.Checked = true;
            } 
            
            if (Properties.Settings.Default.hideProjects)
            {
                this.listView2.Hide();
                this.listView1.Left = this.richTextBox1.Left = 5;
                this.listView1.Width += 180;
                this.richTextBox1.Width = this.listView1.Width;
                this.showProjectInformationToolStripMenuItem.Checked = true;
            } 
            if (Properties.Settings.Default.hideFinished)
            {
                this.hideFinishedTasksToolStripMenuItem.Checked = true;
            }

            //-------------------------
            // Check selected language in toolbar.
            //-------------------------
            switch (Properties.Settings.Default.Language)
            {
                case "pl-PL":
                    this.polskiToolStripMenuItem.Checked = true;
                    break;
                case "fr-FR":
                    this.francaisToolStripMenuItem.Checked = true;
                    break;
                case "es-ES":
                    this.espanolesToolStripMenuItem.Checked = true;
                    break;
                case "en-GB":
                    this.englishToolStripMenuItem.Checked = true;
                    break;
            }
           
            //-------------------------
            // Load recent files list 
            //-------------------------
            if (Properties.Settings.Default.lastTenDatabases.Length > 0)
            {
            	ArrayList lastTen =
            			new ArrayList(
            				Properties.Settings.Default.lastTenDatabases.
            				Replace("||", "|").
            				Remove(Properties.Settings.Default.lastTenDatabases.Length-1, 1).
            				Split(new char[] { '|' }, System.StringSplitOptions.RemoveEmptyEntries));
            	
            	if(Properties.Settings.Default.openLatestDB){
            		this.connectionString = @"Data Source = "+
            			lastTen[0].ToString();
            			//Application.StartupPath+"\\Resources\\base.sdf";
            	}
                for (int i = 0; i < lastTen.Count; ++i)
                {                    
                	if (lastTen[i].ToString().Length > 3){
                		this.openRecentToolStripMenuItem.DropDownItems.Add(lastTen[i].ToString(), list.Images[0],
                    	new EventHandler(openRecentFile));
                	}
                }
                
            }else{
            	//this.openRecentToolStripMenuItem.DropDownItems.Add("No recent files");
            }
          
            try
            {
                //-------------------------
                // Load task list
                //-------------------------
                refreshTaskList();
                //-------------------------
                // Load project list
                //-------------------------
                refreshProjectsList();

            }
            catch (Exception exc)
            {
                Dump(exc);
            }
        }
		
		void MainFormResize(object sender, EventArgs e)
		{           
           this.tableLayoutPanel1.Width  = (this.tabControl1.Width = (int)((double)this.ClientSize.Width))-10;
           this.tableLayoutPanel1.Height = (this.tabControl1.Height = (int)((double)this.ClientSize.Height))-50;
		}
		private void ColumnClick(object o, ColumnClickEventArgs e){
           // Set the ListViewItemSorter property to a new ListViewItemComparer object.
         this.listView1.ListViewItemSorter = new ListViewItemComparer(e.Column);
           // Call the sort method to manually sort the column based on the ListViewItemComparer implementation.
         listView1.Sort();
      }
        void AddNewItemCTRLWToolStripMenuItemClick(object sender, System.EventArgs e){
			Int32 lastId = 0;
            if (this.listView1.Items.Count == 0 || this.listView1.Items[0].Text.ToString()!= "")
            {
                try
                {
                    using (SqlCeConnection connection = new SqlCeConnection(connectionString))
                    {
                        SqlCeCommand cmd = new SqlCeCommand("INSERT INTO task_list(title, project_id, focus_id, status) VALUES(@Title, @Project, @Focus, 0);", connection);
                        cmd.Parameters.Add("@Title", SqlDbType.NVarChar);
                            cmd.Parameters["@Title"].Value = "";

                        cmd.Parameters.Add("@Focus", SqlDbType.Int);
                            cmd.Parameters["@Focus"].Value = this.tabControl1.SelectedIndex;

                        cmd.Parameters.Add("@Project", SqlDbType.Int);
                            cmd.Parameters["@Project"].Value = Convert.ToInt32(this.listView2.SelectedItems[0].SubItems[1].Text);
                        connection.Open();
                        cmd.ExecuteNonQuery();
                        cmd.CommandText = "SELECT @@IDENTITY";                        
                        lastId = int.Parse(cmd.ExecuteScalar().ToString());
                    }
                }
                catch (Exception exc)
                {
                    Dump(exc);
                }
                
                ListViewItem itm = new ListViewItem("", 0);
                itm.SubItems.Add(DateTime.Now.ToString());
                itm.SubItems.Add(""); //why the hell it works with that :D ?
                itm.SubItems.Add(lastId.ToString());
              
                this.listView1.Items.Insert(0, itm);
                this.listView1.Items[0].BeginEdit();
            }
            
            
            Regex dec_regex = new Regex(@"([0-9]+)");
			Match dec_match = dec_regex.Match(this.tabControl1.TabPages[this.tabControl1.SelectedIndex].Text); //Take actual value
			if (dec_match.Success){
				Int32 items_count = Convert.ToInt16(dec_match.Value.ToString()) + 1 ; //Convert and increment it
				this.tabControl1.TabPages[this.tabControl1.SelectedIndex].Text =
					Regex.Replace(this.tabControl1.TabPages[this.tabControl1.SelectedIndex].Text, @"([0-9]+)" , items_count.ToString());
			}
			
			//MessageBox.Show(val.ToString());
			//regex.Replace(test, "")
//			regex.
					

            //this.setNumbers();
		}

        //-------------------------
        // After edit function, updates task.
        //-------------------------
        private void listView1_AfterLabelEdit(object sender, LabelEditEventArgs e)
        {            
            string newTaskText = "";
            int sqlId = 0;
            try
            {
                
                if (this.listView1.Items[e.Item].SubItems.Count > 1)
                {
                    sqlId = Convert.ToInt16(this.listView1.Items[e.Item].SubItems[3].Text);
                }
                if (e.Label != "" && e.Label!= null && e.Label.Length > 0)
                {
                     newTaskText = e.Label.ToString();
                }
                else
                {
                    
                    if (this.listView1.SelectedItems.Count > 0)
                    {                        
                        newTaskText = this.listView1.SelectedItems[0].Text.ToString();
                    }
                    else newTaskText = "";
                }
                using (SqlCeConnection connection = new SqlCeConnection(connectionString))
                {

                    SqlCeCommand cmd =
                        new SqlCeCommand("UPDATE task_list SET title=@Title WHERE id=@Id", connection);
                    cmd.Parameters.Add("@Id", SqlDbType.Int);
                    cmd.Parameters["@Id"].Value = sqlId;
                    cmd.Parameters.Add("@Title", SqlDbType.NVarChar);
                    cmd.Parameters["@Title"].Value = newTaskText;
                    connection.Open();
                    Int32 rowsAffected = cmd.ExecuteNonQuery();
                }
            }
           catch (Exception exc)
           {
               Dump(exc);
           } 
        }

        //-------------------------
        // Occurs when you click on some tab. Then we need to move 
        // widgets from previous tab to actual one
        //-------------------------
        private void tabControl1_Click(object sender, EventArgs e)
        {
           this.SuspendLayout();
        	this.tabControl1.TabPages[this.tabControl1.SelectedIndex].Controls.Add(this.tableLayoutPanel1);
           this.richTextBox1.Text = "";
           refreshTaskList();
            this.ResumeLayout();
        }

        //-------------------------
        // Project activation event. Changing colors etc.
        //-------------------------
        private void listView2_Click(object sender, EventArgs e)
        {
            foreach(ListViewItem tmp in this.listView2.Items){
                tmp.BackColor = Color.White;
                tmp.ForeColor = Color.Gray;
            }
            this.listView2.SelectedItems[0].BackColor = Color.RoyalBlue;
            this.listView2.SelectedItems[0].ForeColor = Color.White;
            
            refreshTaskList();
        }
        //-------------------------
        // On item check. Change colors and update db.
        //-------------------------
        private void listView1_ItemCheck(object sender, ItemCheckEventArgs e)
        {
         //   try
          //  {
                if (this.listView1.Items[e.Index].SubItems[2].Text.ToString() != "1")
                {
                    this.listView1.Items[e.Index].SubItems[2].Text = "1";
                    this.listView1.Items[e.Index].Font = new Font(this.listView1.Font, FontStyle.Strikeout);
                    this.listView1.Items[e.Index].ForeColor = Color.LightGray;                    
                }
                else
                {
                    this.listView1.Items[e.Index].SubItems[2].Text = "0";
                    this.listView1.Items[e.Index].Font = new Font(this.listView1.Font, FontStyle.Regular);
                    this.listView1.Items[e.Index].ForeColor = Color.CornflowerBlue;
                } 
                using (SqlCeConnection connection = new SqlCeConnection(connectionString))
                {
                    SqlCeCommand cmd = new SqlCeCommand("UPDATE task_list SET status = @Status WHERE id=@Id", connection);

                    cmd.Parameters.Add("@Id", SqlDbType.Int);
                    cmd.Parameters["@Id"].Value = Convert.ToInt16(this.listView1.Items[e.Index].SubItems[3].Text);
                    cmd.Parameters.Add("@Status", SqlDbType.Int);
                    cmd.Parameters["@Status"].Value = Convert.ToInt16(this.listView1.Items[e.Index].SubItems[2].Text.ToString());
                    connection.Open();
                    cmd.ExecuteNonQuery();
                }
                //this.setNumbers();
                Regex dec_regex = new Regex(@"([0-9]+)");
				Match dec_match = dec_regex.Match(this.tabControl1.TabPages[this.tabControl1.SelectedIndex].Text); //Take actual value
				Int32 items_count = 0;
				if (dec_match.Success){
					if (Convert.ToInt32(dec_match.Value) > 0 ){
					
					items_count = Convert.ToInt16(dec_match.Value.ToString()) - 1 ; //Convert and increment it
					this.tabControl1.TabPages[this.tabControl1.SelectedIndex].Text =
						Regex.Replace(this.tabControl1.TabPages[this.tabControl1.SelectedIndex].Text, @"([0-9]+)" , items_count.ToString());
					}
				}
                //Hide selected item if needed.
                if (Properties.Settings.Default.hideFinished ///&&
                    //this.listView1.Items[e.Index].SubItems[2].Text.ToString() == "1")
                ){
                    this.listView1.Items[e.Index].Remove();
                }
                else
                {
                    //Move checked item to bottom
                    this.listView1.Items.Insert(
                        this.listView1.Items.Count,
                        (ListViewItem) this.listView1.Items[e.Index].Clone());
                    
                    this.listView1.Items[e.Index].Remove();
                }
           // }
           // catch (Exception exc)
           // {
           //     Dump(exc);
           // }
        }

        //-------------------------
        //Remove project and its items
        //-------------------------
        private void toolStripMenuItem1_Click(object sender, EventArgs e)
        {
            try
            {
                if (MessageBox.Show(rm.GetString("confirmation_delProject"), "", MessageBoxButtons.YesNo) == DialogResult.Yes)
                {
                    int projectId = Convert.ToInt16(this.listView2.SelectedItems[0].SubItems[1].Text); ;
                    using (SqlCeConnection connection = new SqlCeConnection(connectionString))
                    {
                        connection.Open();
                        SqlCeCommand cmd = new SqlCeCommand("DELETE FROM projects WHERE id=@Id", connection);
                        cmd.Parameters.Add("@Id", SqlDbType.Int);
                        cmd.Parameters["@Id"].Value = projectId;
                        cmd.ExecuteNonQuery();

                        this.listView2.SelectedItems[0].Remove();

                        cmd.CommandText = "DELETE FROM task_list WHERE project_id = @Id";
                        cmd.Parameters.Add("@Id", SqlDbType.Int);
                        cmd.Parameters["@Id"].Value = projectId;
                        cmd.ExecuteNonQuery();
                    }
                }
                this.setNumbers();
            }
            catch (Exception exc)
            {
                Dump(exc);
            }
        }

        private void contextMenuStrip2_Opening(object sender, CancelEventArgs e)
        {
            this.renameToolStripMenuItem.Enabled = 
            this.toolStripMenuItem1.Enabled =                 
                this.listView2.SelectedItems.Count > 0 &&
                this.listView2.SelectedItems[0].SubItems[1].Text.ToString() != "0" &&
                this.listView2.SelectedItems.Count > 0;
            
            if (this.listView2.SelectedItems.Count > 0)
            {
                this.toolStripTextBox1.Text =
                    this.listView2.SelectedItems[0].SubItems[0].Text;
            }
        }
        
        //-------------------------
        // Renaming project on enter.
        //-------------------------
        private void onEnter_renameProject(object sender, System.Windows.Forms.KeyPressEventArgs e)
        {
            if (e.KeyChar == (char)13)
            {
                try
                {
                    using (SqlCeConnection connection = new SqlCeConnection(connectionString))
                    {
                        SqlCeCommand cmd = new SqlCeCommand("UPDATE projects SET title=@Title WHERE id=@Id", connection);
                        cmd.Parameters.Add("@Id", SqlDbType.Int);
                        cmd.Parameters["@Id"].Value = Convert.ToInt16(this.listView2.SelectedItems[0].SubItems[1].Text);
                        cmd.Parameters.Add("@Title", SqlDbType.NVarChar);
                        cmd.Parameters["@Title"].Value = this.toolStripTextBox1.Text;

                        connection.Open();
                        cmd.ExecuteNonQuery();
                        this.listView2.SelectedItems[0].Text = this.toolStripTextBox1.Text;
                    }
                }
                catch (Exception exc)
                {
                    Console.WriteLine(exc.Message);
                    MessageBox.Show(rm.GetString("projectExists")); 
                }
                this.contextMenuStrip2.Hide();
            }
        }
        //-------------------------
        // Searching
        //-------------------------
        private void onEnter_search(object sender, System.Windows.Forms.KeyPressEventArgs e)
        {
            if (e.KeyChar == (char)13)
            {
                toolStripMenuItem3_Click(sender, e);
            }
        }
        //-------------------------
        // Inserting project.
        //-------------------------
        private void onEnter_addProject(object sender, System.Windows.Forms.KeyPressEventArgs e)
        {
            if (e.KeyChar == (char)13)
            {
                try
                {
                    Int32 lastInsertId = 0;                 
                    using (SqlCeConnection connection = new SqlCeConnection(connectionString))
                    {
                        connection.Open();
                        SqlCeCommand cmd = new SqlCeCommand("INSERT INTO projects (title) VALUES(@Title)", connection);
                        cmd.Parameters.Add("@Title", SqlDbType.NVarChar);
                        cmd.Parameters["@Title"].Value = this.toolStripTextBox2.Text;
                        cmd.ExecuteNonQuery();
                        cmd.CommandText = "SELECT @@IDENTITY";
                        lastInsertId = int.Parse(cmd.ExecuteScalar().ToString());
                    }
                    ListViewItem newItem = new ListViewItem(this.toolStripTextBox2.Text, 1);
                    newItem.SubItems.Add(lastInsertId.ToString());

                    this.listView2.Items.Add(newItem);
                    this.contextMenuStrip2.Hide();
                }
                catch (Exception exc)
                {
                    Console.WriteLine(exc.Message);
                    MessageBox.Show(rm.GetString("projectExists"));
                }
            }
        }
        private void task_dateChanged(object sender,EventArgs args){
        	        	
        	using (SqlCeConnection connection = new SqlCeConnection(connectionString))
                {
                    SqlCeCommand cmd = new SqlCeCommand("UPDATE task_list SET date = @Date , focus_id=@Focus WHERE id=@Id", connection);
                    cmd.Parameters.Add("@Id", SqlDbType.Int);
                    cmd.Parameters["@Id"].Value = Convert.ToInt16(this.listView1.SelectedItems[0].SubItems[3].Text);
                    cmd.Parameters.Add("@Focus", SqlDbType.Int);
                    cmd.Parameters["@Focus"].Value = 4;//this.tabControl1.SelectedIndex ;
                    cmd.Parameters.Add("@Date", SqlDbType.DateTime);
                    cmd.Parameters["@Date"].Value = this.dateTime.Value;
                    

                    this.listView1.SelectedItems[0].SubItems[1].Text =  this.dateTime.Value.ToString();
                    //this.listView1.SelectedItems[0].Remove();
                    
                    connection.Open();
                    cmd.ExecuteNonQuery();
                }
        	refreshTaskList();
        	
        	this.setNumbers();
        		
        }
        //-------------------------
        // Load note to the task.
        //-------------------------
        private void listView1_onMouseClick(object sender, System.Windows.Forms.MouseEventArgs e)
        {
            using (SqlCeConnection connection = new SqlCeConnection(connectionString))
            {
                try
                {
                    SqlCeCommand cmd = new SqlCeCommand("SELECT note FROM task_list WHERE id=@Id", connection);

                    cmd.Parameters.Add("@Id", SqlDbType.Int);
                    cmd.Parameters["@Id"].Value = Convert.ToInt32(this.listView1.SelectedItems[0].SubItems[3].Text);
                    connection.Open();
                    this.richTextBox1.Text = cmd.ExecuteScalar().ToString();
                }
                catch (Exception exc)
                {
                    Dump(exc);                    
                }
            }                 
        }
        //-------------------------
        // <select> and Focus change
        //-------------------------
        private void toolStripComboBox1_SelectedIndexChanged(object sender, EventArgs e)
        {
            try
            {
                this.listView1.SelectedItems[0].SubItems[4].Text =
                this.toolStripComboBox1.SelectedIndex.ToString();

                this.contextMenuStrip1.Hide();
                using (SqlCeConnection connection = new SqlCeConnection(connectionString))
                {
                    SqlCeCommand cmd = new SqlCeCommand("UPDATE task_list SET focus_id = @Focus WHERE id=@Id", connection);
                    cmd.Parameters.Add("@Id", SqlDbType.Int);
                    cmd.Parameters["@Id"].Value = Convert.ToInt16(this.listView1.SelectedItems[0].SubItems[3].Text);
                    cmd.Parameters.Add("@Focus", SqlDbType.Int);
                    cmd.Parameters["@Focus"].Value = this.toolStripComboBox1.SelectedIndex;

                    this.listView1.SelectedItems[0].Remove();
                    connection.Open();
                    cmd.ExecuteNonQuery();
                }
                this.setNumbers();
            }
            catch (Exception exc)
            {
                Dump(exc);
            }
        }
        //-------------------------
        // <select> and Project change
        //-------------------------
        private void toolStripComboBox2_SelectedIndexChanged(object sender, EventArgs e)
        {
            try
            {
                this.listView1.SelectedItems[0].SubItems[5].Text =
                this.toolStripComboBox2.SelectedIndex.ToString();

                this.contextMenuStrip1.Hide();

                using (SqlCeConnection connection = new SqlCeConnection(connectionString))
                {
                    connection.Open();
                    //As <select> does not support to add subitems to item, we need to
                    //make ID from Project title
                    SqlCeCommand cmd = new SqlCeCommand("SELECT id FROM projects WHERE title=@Title", connection);
                    cmd.Parameters.Add("@Title", SqlDbType.NVarChar);
                    cmd.Parameters["@Title"].Value = this.toolStripComboBox2.SelectedItem.ToString();//Convert.ToInt16(this.listView1.SelectedItems[0].SubItems[3].Text);
                    int projectId = (int)cmd.ExecuteScalar();
                    //And then do proper update ...
                    cmd.CommandText = "UPDATE task_list SET project_id = @Project WHERE id=@Id";
                    cmd.Parameters.Add("@Id", SqlDbType.Int);
                    cmd.Parameters["@Id"].Value = Convert.ToInt16(this.listView1.SelectedItems[0].SubItems[3].Text);
                    cmd.Parameters.Add("@Project", SqlDbType.Int);
                    cmd.Parameters["@Project"].Value = projectId;

                    this.listView1.SelectedItems[0].Remove();
                    cmd.ExecuteNonQuery();
                }
                this.setNumbers();
                
            }
            catch (Exception exc)
            {
                Dump(exc);
            }            
        }
        //-------------------------
        // UI localization, doesn't need restart.
        //-------------------------
        private void LocalizeUI()
        {
            this.Text = "Toodie " + Properties.Settings.Default.Version;
            this.toolStripComboBox1.Items.Add(rm.GetString("Focus_actual"));
            this.toolStripComboBox1.Items.Add(rm.GetString("Focus_tomorrow"));
            this.toolStripComboBox1.Items.Add(rm.GetString("Focus_someday"));
            this.toolStripComboBox1.Items.Add(rm.GetString("Focus_future"));
            this.renameToolStripMenuItem1.Text = rm.GetString("rename");
            this.removeToolStripMenuItem.Text = rm.GetString("remove");// "Remove";
            this.toolStripComboBox1.Text = rm.GetString("changeFocus");
            this.toolStripComboBox2.Text = rm.GetString("changeProject");
            this.tabPage1.Text = rm.GetString("Focus_actual");
            this.columnHeader3.Text = rm.GetString("projects");
            this.toolStripMenuItem1.Text = rm.GetString("remove");
            this.renameToolStripMenuItem.Text = rm.GetString("rename");
            this.addToolStripMenuItem.Text = rm.GetString("add");
            this.columnHeader1.Text = rm.GetString("task");
            this.columnHeader2.Text = rm.GetString("date");
            this.tabPage2.Text = rm.GetString("Focus_tomorrow");

            this.tabPage3.Text = rm.GetString("Focus_someday");
            this.tabPage4.Text = rm.GetString("Focus_future");
            this.tabPage5.Text = rm.GetString("focus_planned");
            this.fileToolStripMenuItem.Text = rm.GetString("file");
            this.loadToolStripMenuItem.Text = rm.GetString("open");
            this.saveToolStripMenuItem.Text = rm.GetString("save");
            this.exportToPDFToolStripMenuItem.Text = rm.GetString("exportToPDF");
            //this.exportAndPrintToolStripMenuItem.Text = rm.GetString("exportAndPrint");
            this.exitToolStripMenuItem.Text = rm.GetString("exit");
            this.vewToolStripMenuItem.Text = rm.GetString("view");
            this.hideFinishedTasksToolStripMenuItem.Text = rm.GetString("hideFinishedTasks");
            this.itemsToolStripMenuItem.Text = rm.GetString("items");

            this.addNewItemCTRLWToolStripMenuItem.Text = rm.GetString("addNewItem");
            this.resetListToolStripMenuItem.Text = rm.GetString("resetList");
            this.toolsToolStripMenuItem.Text = rm.GetString("tools");
            this.findToolStripMenuItem.Text = rm.GetString("find");
            this.importListToolStripMenuItem.Text = rm.GetString("importList");
            this.exportToPDFToolStripMenuItem1.Text = rm.GetString("exportToPDF");
            this.synchronizeToolStripMenuItem.Text = rm.GetString("sync");
            this.withYourFTPToolStripMenuItem.Text = rm.GetString("withYourFTP");
            this.withDotFTPToolStripMenuItem.Text = rm.GetString("withDotFTP");
            this.hToolStripMenuItem.Text = rm.GetString("help");
            this.aboutToddieToolStripMenuItem.Text = rm.GetString("about");
            this.checkForUpdatesToolStripMenuItem.Text = rm.GetString("checkForUpdates");
            this.languageToolStripMenuItem.Text = rm.GetString("language");
            this.noteInformationToolStripMenuItem.Text = rm.GetString("taskInformation");
            this.showProjectInformationToolStripMenuItem.Text = rm.GetString("hideProjects");
            this.newTaskListToolStripMenuItem.Text = rm.GetString("newTaskList");
            this.searchToolStripMenuItem.Text = rm.GetString("search");
            this.openRecentToolStripMenuItem.Text = rm.GetString("openRecent");
            this.highlightToolStripMenuItem.Text = rm.GetString("highlight");
            this.toolStripTextBox3.Text = rm.GetString("search_phrase");
        }

        //-------------------------
        // Dynamically change task note
        //-------------------------
        private void richTextBox1_TextChanged(object sender, EventArgs e)
        {
            if (this.listView1.SelectedItems.Count > 0)
            {
                try
                {
                    using (SqlCeConnection connection = new SqlCeConnection(connectionString))
                    {
                        SqlCeCommand cmd =
                                new SqlCeCommand("UPDATE task_list SET note = @Note WHERE id=@Id", connection);
                        cmd.Parameters.Add("@Id", SqlDbType.Int);
                        cmd.Parameters["@Id"].Value = Convert.ToInt16(this.listView1.SelectedItems[0].SubItems[3].Text);

                        cmd.Parameters.Add("@Note", SqlDbType.NText);
                        cmd.Parameters["@Note"].Value = this.richTextBox1.Text;
                        connection.Open();
                        cmd.ExecuteNonQuery();
                    }
                }
                catch (Exception exc)
                {
                    Dump(exc);
                }
            }
        }
        //-------------------------
        // Remove task
        //-------------------------
        private void removeToolStripMenuItem_Click(object sender, EventArgs e)
        {
            try
            {
                using (SqlCeConnection connection = new SqlCeConnection(connectionString))
                {
                    SqlCeCommand cmd = new SqlCeCommand("DELETE FROM task_list WHERE id=@Id", connection);
                    cmd.Parameters.Add("@Id", SqlDbType.Int);
                    cmd.Parameters["@Id"].Value = Convert.ToInt16(this.listView1.SelectedItems[0].SubItems[3].Text);
                    
                    this.listView1.SelectedItems[0].Remove();
                    connection.Open();
                    cmd.ExecuteNonQuery();
                }
                //this.setNumbers();
                //jesli wpis jest unchecked
                Regex dec_regex = new Regex(@"([0-9]+)");
				Match dec_match = dec_regex.Match(this.tabControl1.TabPages[this.tabControl1.SelectedIndex].Text); //Take actual value
				Int32 items_count = 0;
				if (dec_match.Success){
					if (Convert.ToInt32(dec_match.Value) > 0 ){
					
					items_count = Convert.ToInt16(dec_match.Value.ToString()) - 1 ; //Convert and increment it
					this.tabControl1.TabPages[this.tabControl1.SelectedIndex].Text =
						Regex.Replace(this.tabControl1.TabPages[this.tabControl1.SelectedIndex].Text, @"([0-9]+)" , items_count.ToString());
					}
				}
            }
            catch (Exception exc)
            {
                Dump(exc);
            }
        }
        private void renameToolStripMenuItem1_Click(object sender, EventArgs e)
        {
            if (this.listView1.SelectedItems.Count == 1)
            {
                this.listView1.SelectedItems[0].BeginEdit();
            } 
        }
        //-------------------------
        // Edit on double click
        //-------------------------
        private void listView1_DoubleClick(object sender, EventArgs e)
        {
            if (Properties.Settings.Default.onClickEdit)// this.enableOneClickEdit)
            {
                if (this.listView1.SelectedItems.Count == 1)
                {
                    this.listView1.SelectedItems[0].BeginEdit();
                }
            }
        }       
        private void contextMenuStrip1_Opening(object sender, CancelEventArgs e)
        {
        	this.removeToolStripMenuItem.Enabled =
                this.toolStripComboBox1.Enabled =
                    this.toolStripComboBox2.Enabled = 
                        this.highlightToolStripMenuItem.Enabled = 
                            this.renameToolStripMenuItem1.Enabled = 
        						this.dateTime.Enabled = 
                                	this.listView1.SelectedItems.Count > 0;
            
        }        
        //-------------------------
        // Delete task on "Del" press
        //-------------------------
        private void onKey_delete(object sender, System.Windows.Forms.KeyEventArgs e)
        {
            if (e.KeyCode == Keys.Delete)
            {
                this.richTextBox1.Text = "";
                this.removeToolStripMenuItem_Click(sender, e);
                //this.setNumbers();
                
                Regex dec_regex = new Regex(@"([0-9]+)");
				Match dec_match = dec_regex.Match(this.tabControl1.TabPages[this.tabControl1.SelectedIndex].Text); //Take actual value
				Int32 items_count = 0;
				if (dec_match.Success){
					if (Convert.ToInt32(dec_match.Value) > 0 ){
					
					items_count = Convert.ToInt16(dec_match.Value.ToString()) - 1 ; //Convert and increment it
					this.tabControl1.TabPages[this.tabControl1.SelectedIndex].Text =
						Regex.Replace(this.tabControl1.TabPages[this.tabControl1.SelectedIndex].Text, @"([0-9]+)" , items_count.ToString());
					}
				}
            }
        }

        
        public void requestServer(){
        	  Version newVersion = null;  
            string url = "";
         
        	string xmlURL = Properties.Settings.Default.xmlURL.ToString();
    
                XmlTextReader reader = new XmlTextReader(xmlURL);
                try {
                	reader.MoveToContent();
                } catch (Exception) {
                	MessageBox.Show(rm.GetString("connectionError"), rm.GetString("error"));
                	return;
                }
                string elementName = "";
                if ((reader.NodeType == XmlNodeType.Element) && (reader.Name == "toodie"))
                {
                    while (reader.Read())
                    {
                        if (reader.NodeType == XmlNodeType.Element)
                            elementName = reader.Name;
                        else
                        {
                            if ((reader.NodeType == XmlNodeType.Text) && (reader.HasValue))
                            {
                                switch (elementName)
                                 {
                                    case "version":
                                        newVersion = new Version(reader.Value);
                                        break;
                                    case "url":
                                        url = reader.Value;
                                        break;
                                }
                            }
                        }
                    }
                }
      
                Version curVersion = new Version(Properties.Settings.Default.Version);
                if (curVersion.CompareTo(newVersion) < 0)
                {
                    string title = rm.GetString("newVersion");
                    string question = rm.GetString("download");
                    //updateDialog.Hide();
                    if (DialogResult.Yes ==
                     MessageBox.Show(question, title,
                                     MessageBoxButtons.YesNo,
                                     MessageBoxIcon.Question))
                    { 
                        System.Diagnostics.Process.Start(url);
                    }
                }
                
                reader.Close();  
        }
        //-------------------------
        // Checks for updates
        //-------------------------
        private void checkForUpdatesToolStripMenuItem_Click(object sender, EventArgs e)
        {
          
            //UpdateProgress updateDialog = new UpdateProgress(this, this.rm);
            //updateDialog.Show();
            this.Text = rm.GetString("checking_for_updates");
            
             Thread t = new Thread(new ThreadStart(requestServer));
   			t.Start();
        }

        private void aboutToddieToolStripMenuItem_Click(object sender, EventArgs e)
        {
            AboutBoxLinks aboutBox = new AboutBoxLinks();
            aboutBox.rm = this.rm;
            aboutBox.LocalizeUI();
            aboutBox.Show();
        }
        
        //-------------------------
        //Remove project and its items
        //-------------------------
        private void resetListToolStripMenuItem_Click(object sender, EventArgs e)
        {
            try{
                if (MessageBox.Show(rm.GetString("confirmation_delProject"), "", MessageBoxButtons.YesNo) == DialogResult.Yes)
                {
                    using (SqlCeConnection connection = new SqlCeConnection(connectionString))
                    {
                        SqlCeCommand cmd = new SqlCeCommand("DELETE FROM task_list WHERE project_id=@Id", connection);
                        cmd.Parameters.Add("@Id", SqlDbType.Int);
                        cmd.Parameters["@Id"].Value = Convert.ToInt16(this.listView2.SelectedItems[0].SubItems[1].Text);
                        connection.Open();
                        cmd.ExecuteNonQuery();

                        cmd.CommandText = "DELETE FROM projects WHERE id=@Id";
                        cmd.Parameters.Add("@Id", SqlDbType.Int);
                        cmd.Parameters["@Id"].Value = Convert.ToInt16(this.listView2.SelectedItems[0].SubItems[1].Text);

                        cmd.ExecuteNonQuery();
                        this.listView1.Items.Clear();
                        this.listView2.SelectedItems[0].Remove();
                    }
                    this.setNumbers();
                }
            }catch(Exception exc){
                Dump(exc);
            }
        }
        private void refreshProjectsList()
        {
            try
            {
            	this.listView2.BeginUpdate();
                this.listView2.Items.Clear();
                this.listView2.Items.Add(new ListViewItem(new string[] { rm.GetString("yard"), "1" }, 2));
                using (SqlCeConnection connection = new SqlCeConnection(connectionString))
                {
                    connection.Open();
                    SqlCeCommand cmd = new SqlCeCommand("SELECT * FROM projects WHERE id != 1", connection);
                    SqlCeResultSet resultSet = cmd.ExecuteResultSet(ResultSetOptions.Scrollable | ResultSetOptions.Updatable);
                    SqlCeDataReader rdr = cmd.ExecuteReader();
                    while (rdr.Read())
                    {
                        string[] itemData = new string[] { 
		                            rdr["title"].ToString(), //0
		                            rdr["id"].ToString(), //1		                           
		                        };
                        this.toolStripComboBox2.Items.Add(itemData[0]);
                        ListViewItem itm = new ListViewItem(itemData, 1);
                        this.listView2.Items.Add(itm);
                    }
                    rdr.Close();
                }
                //-------------------------
                // Set first project as selected. Which is "yard".
                //-------------------------

                this.listView2.Items[0].Selected = true;
                this.listView2.HideSelection = false;
                this.listView2.Items[0].EnsureVisible();
                this.listView2.EndUpdate();
                this.setNumbers();

            }
            catch (Exception exc)
            {
                Dump(exc);
                MessageBox.Show(rm.GetString("nodatabase"));
            }
              
        }
        private void setNumbers(){
        	
        	//odpalac przy uruchomieniu
        	//nastepnie przy usunieciu/dodaniu zadania, 
        	//wywolaj funkcje incr/decr number (a podstawie NAZWA (numer)
        	//pamietac o projekcie do tego tez.
        	string query = "SELECT COUNT(1) as c FROM task_list WHERE project_id=@Pid AND focus_id = @Fid and status=0";
        	 
              int projectId = 1;

	            if (this.listView2.SelectedItems.Count > 0)
	            {
	                projectId = Convert.ToInt16(this.listView2.SelectedItems[0].SubItems[1].Text);
	            }
                
                using (SqlCeConnection connection = new SqlCeConnection(connectionString))
                {
                	connection.Open();
                	ArrayList names = new ArrayList();
                	names.Add("Focus_actual");
                	names.Add("Focus_tomorrow");
                	names.Add("Focus_someday");
                	names.Add("Focus_future");
                	names.Add("focus_planned");
                	
                	        
                	for(int i = 0; i < 5; ++i){
                    SqlCeCommand cmd = new SqlCeCommand(query, connection);
                    cmd.Parameters.Add("@Pid", SqlDbType.Int);
                    cmd.Parameters["@Pid"].Value = projectId;
                    cmd.Parameters.Add("@Fid", SqlDbType.Int);
                
                    
                    	cmd.Parameters["@Fid"].Value = i;
                    	
		                   // SqlCeResultSet resultSet = cmd.ExecuteResultSet(ResultSetOptions.Scrollable | ResultSetOptions.Updatable);
		                    SqlCeDataReader rdr = cmd.ExecuteReader();
		                    while(rdr.Read()){
		                    	//if ( Convert.ToInt16(rdr["c"]) > 0){
		                    	this.tabControl1.TabPages[i].Text = rm.GetString(names[i].ToString()) + " (" + rdr["c"].ToString() +")";
		                    	//}
		                    	//MessageBox.Show(rdr["c"] .ToString());
		                    }
                	}

                }
        }
        //-------------------------
        // Refreshes task list after some operations and settings changes.
        //-------------------------
        private void refreshTaskList()
        {
            try
            {
            	listView1.BeginUpdate(); 
                bool hideFinished = !Properties.Settings.Default.hideFinished;
                int focusId = this.tabControl1.SelectedIndex;
                int projectId = 1;

                if (this.listView2.SelectedItems.Count > 0)
                {
                    projectId = Convert.ToInt16(this.listView2.SelectedItems[0].SubItems[1].Text);
                }
               
                string Query = "SELECT * FROM task_list WHERE project_id=@Pid AND focus_id = @Fid ORDER BY position ASC";
                string Query_withoutFinished = "SELECT * FROM task_list WHERE project_id=@Pid AND focus_id = @Fid AND status=@Sid ORDER BY position, id ASC";
               
                using (SqlCeConnection connection = new SqlCeConnection(connectionString))
                {
                    
                    string query = Properties.Settings.Default.hideFinished ? Query_withoutFinished : Query;
                    SqlCeCommand cmd = new SqlCeCommand(query, connection);
                    cmd.Parameters.Add("@Pid", SqlDbType.Int);
                    cmd.Parameters["@Pid"].Value = projectId;
                    cmd.Parameters.Add("@Fid", SqlDbType.Int);
                    cmd.Parameters["@Fid"].Value = focusId;

                    if (Properties.Settings.Default.hideFinished)
                    {
                        cmd.Parameters.Add("@Sid", SqlDbType.Int);
                        cmd.Parameters["@Sid"].Value = hideFinished;
                    }
                    connection.Open();
                    SqlCeResultSet resultSet = cmd.ExecuteResultSet(ResultSetOptions.Scrollable | ResultSetOptions.Updatable);
                    SqlCeDataReader rdr = cmd.ExecuteReader();

                    this.listView1.Items.Clear();
                    
                    while (rdr.Read())
                    {
                        ListViewItem itm = new ListViewItem(
                                new string[] { 
		                            rdr["title"].ToString(), //0
		                            rdr["date"].ToString(), //1
		                            rdr["status"].ToString(), //2
		                            rdr["id"].ToString(), //3
                                    rdr["focus_id"].ToString(), //4
                                    rdr["project_id"].ToString() //5
		                        }, 0);

                        if (rdr["status"].ToString() == "1")
                        {
                            itm.ForeColor = Color.LightGray;
                            itm.Font = new Font(listView1.Font, FontStyle.Strikeout);
                        }
                        if (rdr["highlight"].ToString() == "True")
                        {
                            itm.BackColor = Color.LightBlue;
                        }
                        this.listView1.Items.Add(itm);
                    }
                }
                listView1.EndUpdate();
                this.setNumbers();

            }
            catch (Exception exc)
            {
                Dump(exc);
            }
        }

        //-------------------------
        // Hides finished tasks.
        //-------------------------
        private void hideFinishedTasksToolStripMenuItem_Click(object sender, EventArgs e)
        {
            this.hideFinishedTasksToolStripMenuItem.Checked = !this.hideFinishedTasksToolStripMenuItem.Checked;            
            Properties.Settings.Default.hideFinished = !Properties.Settings.Default.hideFinished;
            Properties.Settings.Default.Save();
            refreshTaskList();
        }
        //-------------------------
        // Changes language.
        //-------------------------
        private void changeLanguage(object sender, EventArgs e)
        {
            string languageCode="";            
            //Disable previous checks
            foreach (ToolStripMenuItem itm in this.languageToolStripMenuItem.DropDownItems)
            {
                itm.Checked = false;
            }
            
            switch (sender.ToString())
            {      
                case "Polski":
                    languageCode = "pl-PL";
                    this.polskiToolStripMenuItem.Checked = true;
                    break;
                case "Francais":
                    languageCode = "fr-FR";
                    this.francaisToolStripMenuItem.Checked = true;
                    break;
                case "Españoles":
                    languageCode = "es-ES";
                    this.espanolesToolStripMenuItem.Checked = true;
                    break;
                case "English":
                    languageCode = "en-GB";
                    this.englishToolStripMenuItem.Checked = true;
                    break;
            }
            CultureInfo ci = new CultureInfo(languageCode);
            Thread.CurrentThread.CurrentCulture=ci;
            Thread.CurrentThread.CurrentUICulture=ci;
            
            LocalizeUI();
            
            Properties.Settings.Default.Language = languageCode;
            Properties.Settings.Default.Save();
        }

        //-------------------------
        // Shows/Hides notes textarea.
        //-------------------------
        private void noteInformationToolStripMenuItem_Click(object sender, EventArgs e)
        {
            this.noteInformationToolStripMenuItem.Checked = !this.noteInformationToolStripMenuItem.Checked;
            if (this.noteInformationToolStripMenuItem.Checked)
            {
                this.richTextBox1.Hide();
                this.listView1.Height += 90;
            }
            else
            {
                this.richTextBox1.Show();
                this.listView1.Height -=90;
            }
            Properties.Settings.Default.hideNotes = !Properties.Settings.Default.hideNotes;
            Properties.Settings.Default.Save();
        }
        
        //-------------------------
        // Shows/Hides project list
        //-------------------------
        private void showProjectInformationToolStripMenuItem_Click(object sender, EventArgs e)
        {
            this.showProjectInformationToolStripMenuItem.Checked = !this.showProjectInformationToolStripMenuItem.Checked;

            if (this.showProjectInformationToolStripMenuItem.Checked)
            {
                this.listView2.Hide();
                this.listView1.Left = this.richTextBox1.Left = 5;
                this.listView1.Width += 180;
                this.richTextBox1.Width = this.listView1.Width;
            }
            else
            {
                this.listView2.Show();
                this.listView1.Left = this.richTextBox1.Left = 180; //?? ok ??
                this.listView1.Width -= 180;
                this.richTextBox1.Width = this.listView1.Width;
            }
            Properties.Settings.Default.hideProjects = !Properties.Settings.Default.hideProjects;
            Properties.Settings.Default.Save();
        }
        private void searchResultsList(string search)
        {
            using (SqlCeConnection connection = new SqlCeConnection(connectionString))
            {
                SqlCeCommand cmd = new SqlCeCommand("SELECT * FROM task_list WHERE title LIKE @Title", connection);
                cmd.Parameters.Add("@Title", SqlDbType.NVarChar);
                cmd.Parameters["@Title"].Value =  '%' +search + '%';
                
                try
                {
                    connection.Open();
                    SqlCeResultSet resultSet = cmd.ExecuteResultSet(ResultSetOptions.Scrollable | ResultSetOptions.Updatable);
                    SqlCeDataReader rdr = cmd.ExecuteReader();
                    int i = 0;

                    this.listView1.Items.Clear();
                    this.listView1.SuspendLayout();
                    
                    this.listView1.BeginUpdate();
                    while (rdr.Read())
                    {
                        i++;
                        ListViewItem itm = new ListViewItem(
                                new string[] { 
		                            rdr["title"].ToString(), //0
		                            rdr["date"].ToString(), //1
		                            rdr["status"].ToString(), //2
		                            rdr["id"].ToString(), //3
                                    rdr["focus_id"].ToString(), //4
                                    rdr["project_id"].ToString() //5
		                        }, 0);

                        if (rdr["status"].ToString() == "1")
                        {
                            itm.ForeColor = Color.LightGray;
                            itm.Font = new Font(listView1.Font, FontStyle.Strikeout);
                        }
                        if (rdr["highlight"].ToString() == "True")
                        {
                            itm.BackColor = Color.LightBlue;
                        }
                        this.listView1.Items.Add(itm);
                    }
                    this.listView1.EndUpdate();
                    this.listView1.ResumeLayout();
                    
                }
                catch (Exception exc)
                {
                    Dump(exc);
                }
            }
        }
        private void toolStripMenuItem3_Click(object sender, EventArgs e)
        {
            if (this.toolStripTextBox3.Text.Length == 0)
            {
                refreshTaskList();
            }
            else
            {
                searchResultsList(this.toolStripTextBox3.Text);
            }
        }

        private void exportToPDFToolStripMenuItem_Click(object sender, EventArgs e)
        {
            ExportBox bForm = new ExportBox();
            bForm.setListView(this.listView1);
            bForm.setProjectsListView(this.listView2);
            bForm.setFocusTab(this.tabControl1);
            bForm.setResMan(ref this.rm);
            
            if (this.listView2.SelectedItems.Count > 0)
            {
                
                bForm.actualProject = Convert.ToInt16(this.listView2.SelectedItems[0].SubItems[1].Text);
            }
            else
            {
                bForm.actualProject = -1;
                
            }                
            bForm.actualFocus = tabControl1.SelectedIndex;
            bForm.setConnectionString(this.connectionString);

            if (bForm.actualProject == -1)
            {
                bForm.radioButton3.Enabled = bForm.radioButton4.Enabled = false;
            }

            bForm.Show();      
        }
        private void searchToolStripMenuItem_Click(object sender, EventArgs e)
        {
            this.toolStripTextBox3.Focus();
        }

        private void exitToolStripMenuItem_Click(object sender, EventArgs e)
        {
            this.Dispose();
        }
        private void highlightToolStripMenuItem_Click(object sender, EventArgs e)
        {
            using (SqlCeConnection connection = new SqlCeConnection(connectionString))
            {
                SqlCeCommand cmd = new SqlCeCommand("UPDATE task_list SET highlight=~highlight WHERE id=@Id", connection);
                cmd.Parameters.Add("@Id", SqlDbType.TinyInt);
                cmd.Parameters["@Id"].Value = Convert.ToInt16(this.listView1.SelectedItems[0].SubItems[3].Text);

                try
                {
                    connection.Open();
                    cmd.ExecuteNonQuery();

                    if (this.listView1.SelectedItems[0].BackColor == Color.LightBlue)
                    {
                        this.listView1.SelectedItems[0].BackColor = Color.White;
                    }
                    else
                    {
                        this.listView1.SelectedItems[0].BackColor = Color.LightBlue;
                    }                        
                }
                catch (Exception exc)
                {
                    Dump(exc);
                }
            }
        }
        private void newTaskListToolStripMenuItem_Click(object sender, EventArgs e)
        {
            SaveFileDialog saveDialog = new SaveFileDialog();
            saveDialog.AddExtension = true;
            saveDialog.DefaultExt = ".sdf";
            saveDialog.FileName = rm.GetString("mynewtasklist");
            
            saveDialog.Filter = "SDF (*.sdf)|*.sdf";
            saveDialog.Title = rm.GetString("mynewtasklist");
            if (saveDialog.ShowDialog() == DialogResult.OK)
            {                
                File.Copy(Application.StartupPath + "\\Resources\\empty_database.sdf", saveDialog.FileName);
            }
        }
        private void alwaysOpenLatestDB_Click(object sender, EventArgs e){
            this.alwaysOpenLatestDBToolStripMenuItem.Checked = Properties.Settings.Default.openLatestDB = !Properties.Settings.Default.openLatestDB;
            Properties.Settings.Default.Save();
        }
        private void loadToolStripMenuItem_Click(object sender, EventArgs e)
        {
            OpenFileDialog od = new OpenFileDialog();
            od.AddExtension = true;
            od.DefaultExt = ".sdf";
            od.Filter = "SDF (*.sdf)|*.sdf";
            od.Title = rm.GetString("opentasklist");
            if (od.ShowDialog() == DialogResult.OK)
            {
                this.connectionString = @"Data Source = " + od.FileName;
                refreshTaskList();
                refreshProjectsList();
                Properties.Settings.Default.lastDatabaseFile = this.connectionString;
                Properties.Settings.Default.Save();
                this.Text = "Toodie - " + od.FileName;
                ArrayList lastTen = new ArrayList(Properties.Settings.Default.lastTenDatabases.Replace("||", "|").Split(new char[] { '|' }, StringSplitOptions.RemoveEmptyEntries));                
                if (lastTen.Count >= 10)
                {
                    lastTen.Insert(0, od.FileName);
                }
                else
                {
             
                		lastTen.Add(od.FileName);
                }
                
                this.openRecentToolStripMenuItem.DropDownItems.Add(od.FileName, Image.FromFile(Application.StartupPath + "\\Resources\\document.gif"));
                string toSave = "";
                foreach (object str in lastTen)
                {
                    if (str.ToString().Length > 4)
                    {
                        toSave += str + "|";
                    }
                }
                
                Properties.Settings.Default.lastTenDatabases = toSave;
                Properties.Settings.Default.Save();
            }

        }
        private void openRecentFile(object sender, EventArgs e)
        {
            this.connectionString = @"Data Source = " + sender.ToString();
            refreshTaskList();
            refreshProjectsList();
            Properties.Settings.Default.lastDatabaseFile = this.connectionString;
            ArrayList lastTen = new ArrayList(Properties.Settings.Default.lastTenDatabases.Replace("||", "|").Split(new char[] { '|' }, StringSplitOptions.RemoveEmptyEntries));                
            if (lastTen.Count >= 10)
            {
                lastTen.Insert(0, sender.ToString());
            }
            else
            {
            	
            		lastTen.Add(sender.ToString());
                	
            }
            string toSave = "";
            foreach (object str in lastTen)
            {
            	if(str.ToString().Length > 3){                
            		toSave += str + "|";
            	}
            }
            
            Properties.Settings.Default.lastTenDatabases = toSave;
            Properties.Settings.Default.Save();

           
            this.Text = "Toodie - " + sender.ToString();
            this.setNumbers();

        }

        private void listView1_ItemDrag(object sender, ItemDragEventArgs e)
        {
            listView1.DoDragDrop(listView1.SelectedItems, DragDropEffects.Copy | DragDropEffects.Move);
        }

        private void listView1_DragEnter(object sender, DragEventArgs e)
        {
            int len = e.Data.GetFormats().Length - 1;
            int i;
            for (i = 0; i <= len; i++)
            {
                if (e.Data.GetFormats()[i].Equals("System.Windows.Forms.ListView+SelectedListViewItemCollection"))
                {
                    //The data from the drag source is moved to the target.	
                    e.Effect = DragDropEffects.Move;
                }
            }
        }

        private void listView1_DragDrop(object sender, DragEventArgs e)
        {
            //Return if the items are not selected in the ListView control.
            if (listView1.SelectedItems.Count == 0)
            {
                return;
            }
            //Returns the location of the mouse pointer in the ListView control.
            Point cp = listView1.PointToClient(new Point(e.X, e.Y));
            //Obtain the item that is located at the specified location of the mouse pointer.
            ListViewItem dragToItem = listView1.GetItemAt(cp.X, cp.Y);
            if (dragToItem == null)
            {
                return;
            }
            //Obtain the index of the item at the mouse pointer.
            int dragIndex = dragToItem.Index;
            ListViewItem[] sel = new ListViewItem[listView1.SelectedItems.Count];
            for (int i = 0; i <= listView1.SelectedItems.Count - 1; i++)
            {
                sel[i] = listView1.SelectedItems[i];
            }
            for (int i = 0; i < sel.GetLength(0); i++)
            {
                //Obtain the ListViewItem to be dragged to the target location.
                ListViewItem dragItem = sel[i];
                int itemIndex = dragIndex;
                if (itemIndex == dragItem.Index)
                {
                    return;
                }
                if (dragItem.Index < itemIndex)
                    itemIndex++;
                else
                    itemIndex = dragIndex + i;
                //Insert the item at the mouse pointer.
                ListViewItem insertItem = (ListViewItem)dragItem.Clone();
                listView1.Items.Insert(itemIndex, insertItem);
                //Removes the item from the initial location while 
                //the item is moved to the new location.
                listView1.Items.Remove(dragItem);
                try
                {
                    using (SqlCeConnection connection = new SqlCeConnection(connectionString))
                    {
                        SqlCeCommand cmd = new SqlCeCommand("UPDATE task_list SET position=@Pos WHERE id=@Id;", connection);
                        cmd.Parameters.Add("@Pos", SqlDbType.Int);
                        cmd.Parameters["@Pos"].Value = itemIndex;

                        cmd.Parameters.Add("@Id", SqlDbType.Int);
                        cmd.Parameters["@Id"].Value = Convert.ToInt16(dragItem.SubItems[3].Text);

                        connection.Open();
                        cmd.ExecuteNonQuery();
                    }
                }
                catch (Exception exc)
                {
                    Dump(exc);
                }
            }            
        }

        private void toolStripTextBox3_Click(object sender, EventArgs e)
        {
            if (this.toolStripTextBox3.Text == rm.GetString("search_phrase"))
            {
                this.toolStripTextBox3.Text = "";
            }
        }

        private void renameToolStripMenuItem1_Click_1(object sender, EventArgs e)
        {
            if (this.listView1.SelectedItems.Count == 1)
            {
                this.listView1.SelectedItems[0].BeginEdit();
            } 
        }
        private void Dump(Exception e)
        {
            using (StreamWriter sw = new StreamWriter(Application.StartupPath + "\\dump.txt"))
            {
                System.Diagnostics.StackTrace st = new System.Diagnostics.StackTrace(true);
                System.Diagnostics.StackFrame sf = st.GetFrame(1);
                Console.WriteLine(e.Message);
                sw.WriteLine("// Bug report file. Version " + Properties.Settings.Default.Version + ", " + System.DateTime.Today.ToString() +"\\");
                sw.WriteLine("--------------------------------------------------------");
                sw.WriteLine(e.Message);
                sw.WriteLine("Trace "
                    + sf.GetMethod().Name + " "
                    + sf.GetFileName() + ":"
                    + sf.GetFileLineNumber());
            }
        }
       
		
		void SaveToolStripMenuItemClick(object sender, EventArgs e)
		{
			SaveFileDialog saveDialog = new SaveFileDialog();
            saveDialog.AddExtension = true;
            saveDialog.DefaultExt = ".sdf";
            saveDialog.FileName = "backup-" +DateTime.Now.ToString("d-m-y HH-mm");
            
            saveDialog.Filter = "SDF (*.sdf)|*.sdf";
            saveDialog.Title = rm.GetString("createbackup");
            if (saveDialog.ShowDialog() == DialogResult.OK)
            {                
                File.Copy(Application.StartupPath + "\\Resources\\base.sdf", saveDialog.FileName);
            }
         
		}
		
		
	
		
		void ToolStripTextBox3KeyUp(object sender, KeyEventArgs e)
		{
			searchResultsList(this.toolStripTextBox3.Text);
		}
	}
}
