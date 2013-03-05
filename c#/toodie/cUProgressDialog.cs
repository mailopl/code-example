using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Toodie
{
    public  partial class UpdateProgress : Form
    {
        public UpdateProgress(Control parent, System.Resources.ResourceManager rm)
        {
            InitializeComponent();
            this.progressBar1.Maximum = 100;
            this.progressBar1.Value = 5;            
            this.Parent = Parent;
            this.CenterToParent();
            this.label1.Text = rm.GetString("searching_updates");
            this.Text = rm.GetString("");
        }

        public void setProgress(int progress)
        {
            this.progressBar1.Value = progress;
        }
        private void timer1_Tick(object sender, EventArgs e)
        {
            if (this.progressBar1.Value < this.progressBar1.Maximum)
            {
                this.progressBar1.Value++;
            }
            else 
            {
                this.progressBar1.Value-=10;
            }
        } 
    }
}
